<?php

namespace App\Http\Controllers;

use App\Models\Projet;
use App\Models\Analyse;
use App\Models\Erreur;
use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use ZipArchive;
use Carbon\Carbon;

class ProjetController extends Controller
{
    // Afficher le formulaire d'upload
    public function showUploadForm()
    {
        return view('projects.upload');
    }

    // Stocker le projet et lancer l'analyse automatiquement
    public function store(Request $request)
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'type_depot' => 'required|in:ZIP,GitHub',
        ];

        if ($request->type_depot === 'ZIP') {
            $rules['fichier_zip'] = 'required|file|mimes:zip|max:2097152';
        } else {
            $rules['lien_depot'] = 'required|url';
        }

        $validated = $request->validate($rules);

        try {
            // Création du projet
            $projet = new Projet();
            $projet->user_id = Auth::id();
            $projet->nom = $validated['nom'];
            $projet->type_depot = $validated['type_depot'];
            $projet->statut = 'en_attente'; // doit être une valeur valide ENUM

            // Gestion dépôt ZIP ou GitHub
            if ($validated['type_depot'] === 'ZIP') {
                $projet->lien_depot = $this->handleZipUpload($request->file('fichier_zip'), $projet->nom);
            } else {
                $projet->lien_depot = $this->cloneGithubRepo($validated['lien_depot'], $projet->nom);
            }

            // Sauvegarde pour générer ID
            $projet->save();
            $projet->refresh();
            \Log::info('Projet ID avant analyse', ['id' => $projet->id]);

            // Lancer l'analyse automatiquement
            $this->lancerAnalyseInterne($projet);

            return redirect()
                ->route('user.dashboard')
                ->with('success', "Projet '{$projet->nom}' importé avec succès ! L'analyse est en cours...");

        } catch (\Exception $e) {
            \Log::error('Erreur store projet', ['message' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', "Erreur lors de l'import du projet : " . $e->getMessage());
        }
    }

    // Upload ZIP et extraction
    private function handleZipUpload($file, $projectName)
    {
        $folderName = Str::slug($projectName) . '_' . time();
        $storagePath = storage_path('app/projects/' . $folderName);

        if (!file_exists($storagePath)) mkdir($storagePath, 0755, true);

        $zipPath = $storagePath . '/archive.zip';
        $file->move($storagePath, 'archive.zip');

        $zip = new ZipArchive();
        if ($zip->open($zipPath) === true) {
            $zip->extractTo($storagePath);
            $zip->close();
            unlink($zipPath);
        } else {
            throw new \Exception("Impossible d'extraire le fichier ZIP");
        }

        return 'projects/' . $folderName;
    }

    // Cloner dépôt GitHub
    private function cloneGithubRepo($githubUrl, $projectName)
    {
        $folderName = Str::slug($projectName) . '_' . time();
        $storagePath = storage_path('app/projects/' . $folderName);

        if (!file_exists($storagePath)) mkdir($storagePath, 0755, true);

        $command = "git clone {$githubUrl} {$storagePath} 2>&1";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new \Exception("Erreur lors du clonage : " . implode("\n", $output));
        }

        return 'projects/' . $folderName;
    }

    // Méthode interne pour lancer l'analyse d'un projet
    private function lancerAnalyseInterne(Projet $projet)
    {
        if (!$projet->id) {
            throw new \Exception('Projet ID manquant avant analyse');
        }

        try {
            // Mettre à jour le statut (valeur ENUM valide)
            $projet->update(['statut' => 'analyse_en_cours']);

            $pythonApiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000');

            $payload = [
                'projet_id' => $projet->id,
                'text' => 'Analyse du projet ' . $projet->nom,
            ];

            \Log::info('Payload envoyé à Flask', $payload);

            $response = Http::timeout(300)
                ->asJson()
                ->post($pythonApiUrl . '/analyse', $payload);

            if (!$response->successful()) {
                throw new \Exception("Erreur API Flask");
            }

            $data = $response->json();
            \Log::info('Réponse Flask', $data);

            if (!isset($data['success']) || $data['success'] !== true) {
                throw new \Exception($data['message'] ?? 'Analyse échouée');
            }

            if (!isset($data['resultats'])) {
                throw new \Exception("Résultats manquants");
            }

            // Sauvegarder les résultats
            $this->sauvegarderResultats($projet, $data['resultats']);
            $projet->update(['statut' => 'analyse_terminee']);

        } catch (\Exception $e) {
            $projet->update(['statut' => 'en_attente']);
            \Log::error('Erreur analyse', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    // Méthode publique pour lancer l'analyse via route POST /projects/{id}/analyze
    public function lancerAnalyse($id)
    {
        $projet = Projet::find($id);

        if (!$projet) {
            return redirect()->back()->with('error', 'Projet introuvable ou ID manquant.');
        }

        $this->lancerAnalyseInterne($projet);

        return redirect()->route('projects.show', $projet->id)
                         ->with('success', 'Analyse lancée avec succès !');
    }

    // Sauvegarder résultats dans DB
    private function sauvegarderResultats(Projet $projet, array $resultats)
    {
        \Log::info('Projet ID dans sauvegarde', ['id' => $projet->id]);

        $scoreQualite = $resultats['score_qualite'] ?? 0;

        $analyse = Analyse::create([
            'projet_id' => $projet->id,
            'score_qualite' => $scoreQualite,
            'date_analyse' => Carbon::now(),
        ]);

        $projet->update(['score_qualite' => $scoreQualite]);

        foreach ($resultats['erreurs'] ?? [] as $erreurData) {
            $erreur = Erreur::create([
                'analyse_id' => $analyse->id,
                'categorie_id' => $erreurData['categorie_id'],
                'fichier' => $erreurData['fichier'],
                'ligne' => $erreurData['ligne'],
                'description' => $erreurData['description'],
                'gravite' => $erreurData['gravite'],
            ]);

            if (!empty($erreurData['suggestion'])) {
                Suggestion::create([
                    'erreur_id' => $erreur->id,
                    'suggestion' => $erreurData['suggestion'],
                ]);
            }
        }
    }

    // Afficher un projet avec ses analyses et erreurs
    public function show($id)
    {
        $projet = Projet::with('analyses.erreurs.suggestion')->find($id);

        if (!$projet) {
            return redirect()->route('projects.index')
                             ->with('error', 'Projet introuvable.');
        }

        return view('projects.show', compact('projet'));
    }

    // Vérifier si l'API Python est disponible
    public function checkPythonApi()
    {
        try {
            $pythonApiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000');
            $response = Http::timeout(5)->get($pythonApiUrl . '/health');

            if ($response->successful()) {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'API Python opérationnelle',
                    'data' => $response->json()
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'API Python non accessible'
                ], 503);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'API Python non disponible : ' . $e->getMessage()
            ], 503);
        }
    }
}