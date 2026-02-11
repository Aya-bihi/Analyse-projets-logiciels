<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ProjetController extends Controller
{
    /**
     * Afficher la page d'upload
     */
    public function showUploadForm()
    {
        return view('projects.upload');
    }

    /**
     * Traiter l'upload et créer le projet
     */
    public function store(Request $request)
    {
        // Validation
        $rules = [
            'nom' => 'required|string|max:255',
            'type_depot' => 'required|in:ZIP,GitHub',
        ];

        if ($request->type_depot === 'ZIP') {
            $rules['fichier_zip'] = 'required|file|mimes:zip|max:51200'; // 50 MB max
        } else {
            $rules['lien_depot'] = 'required|url';
        }

        $validated = $request->validate($rules);

        try {
            // Créer le projet dans la BD
            $projet = new Projet();
            $projet->user_id = Auth::id();
            $projet->nom = $validated['nom'];
            $projet->type_depot = $validated['type_depot'];
            $projet->statut = 'en_attente';

            if ($validated['type_depot'] === 'ZIP') {
                // Traiter le fichier ZIP
                $chemin = $this->handleZipUpload($request->file('fichier_zip'), $projet->nom);
                $projet->lien_depot = $chemin;
            } else {
                // Stocker le lien GitHub
                $projet->lien_depot = $validated['lien_depot'];
                
                // Optionnel : Cloner le dépôt GitHub immédiatement
                // $this->cloneGithubRepo($validated['lien_depot'], $projet->nom);
            }

            $projet->save();

            // Optionnel : Lancer l'analyse automatiquement
            // $this->lancerAnalyse($projet);

            return redirect()
                ->route('user.dashboard')
                ->with('success', "Projet '{$projet->nom}' importé avec succès ! L'analyse va démarrer.");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', "Erreur lors de l'import du projet : " . $e->getMessage());
        }
    }

    /**
     * Gérer l'upload du fichier ZIP
     */
    private function handleZipUpload($file, $projectName)
    {
        // Créer un nom de dossier unique
        $folderName = Str::slug($projectName) . '_' . time();
        $storagePath = storage_path('app/projects/' . $folderName);

        // Créer le dossier
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Sauvegarder le ZIP temporairement
        $zipPath = $storagePath . '/archive.zip';
        $file->move($storagePath, 'archive.zip');

        // Extraire le ZIP
        $zip = new ZipArchive();
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($storagePath . '/extracted');
            $zip->close();
            
            // Optionnel : Supprimer le ZIP après extraction
            // unlink($zipPath);
        } else {
            throw new \Exception("Impossible d'extraire le fichier ZIP");
        }

        // Retourner le chemin relatif
        return 'projects/' . $folderName;
    }

    /**
     * Cloner un dépôt GitHub (optionnel)
     */
    private function cloneGithubRepo($githubUrl, $projectName)
    {
        // Créer un nom de dossier unique
        $folderName = Str::slug($projectName) . '_' . time();
        $storagePath = storage_path('app/projects/' . $folderName);

        // Créer le dossier
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }

        // Cloner le dépôt avec Git
        $command = "git clone {$githubUrl} {$storagePath} 2>&1";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Erreur lors du clonage du dépôt : " . implode("\n", $output));
        }

        return 'projects/' . $folderName;
    }

    /**
     * Afficher tous les projets de l'utilisateur
     */
    public function index()
    {
        $projets = Projet::where('user_id', Auth::id())
            ->with('analyses')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.index', compact('projets'));
    }

    /**
     * Afficher un projet spécifique
     */
    public function show($id)
    {
        $projet = Projet::where('user_id', Auth::id())
            ->with(['analyses.erreurs.categorie', 'analyses.erreurs.suggestions'])
            ->findOrFail($id);

        return view('projects.show', compact('projet'));
    }

    /**
     * Supprimer un projet
     */
    public function destroy($id)
    {
        $projet = Projet::where('user_id', Auth::id())->findOrFail($id);

        // Supprimer les fichiers du projet
        $projectPath = storage_path('app/' . $projet->lien_depot);
        if (file_exists($projectPath)) {
            $this->deleteDirectory($projectPath);
        }

        // Supprimer le projet de la BD (cascade supprime analyses, erreurs, etc.)
        $projet->delete();

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Projet supprimé avec succès');
    }

    /**
     * Supprimer un dossier récursivement
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }

        if (!is_dir($dir)) {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Lancer l'analyse d'un projet (à appeler depuis un job ou directement)
     */
   public function lancerAnalyse(Projet $projet)
{
    // ✅ NE PAS créer un nouveau Projet
    // ✅ juste modifier celui qui existe déjà

    $projet->update([
        'statut' => 'analyse_terminee'
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Analyse terminée'
    ]);
}



       
    
}

 // Exemple d'appel à l'API Python (à implémenter)
        /*
        $pythonApiUrl = env('PYTHON_API_URL', 'http://localhost:5000');
        $response = Http::post($pythonApiUrl . '/analyze', [
            'project_path' => storage_path('app/' . $projet->lien_depot),
            'project_id' => $projet->id
        ]);
        */