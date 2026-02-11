<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Projet;
use App\Models\Analyse;
use App\Models\Erreur;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Afficher le dashboard de l'utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les projets récents (les 5 derniers)
        $projets_recents = Projet::where('user_id', $user->id)
            ->with(['analyses.erreurs.categorie'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Calculer les statistiques
        $stats = $this->calculerStatistiques($user->id);

        return view('user.dashboard', compact('projets_recents', 'stats'));
    }

    /**
     * Calculer les statistiques pour le dashboard
     */
    private function calculerStatistiques($user_id)
    {
        // Total des analyses
        $total_analyses = Analyse::whereHas('projet', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->count();

        // Analyses de cette semaine
        $analyses_semaine = Analyse::whereHas('projet', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->where('created_at', '>=', Carbon::now()->subWeek())
        ->count();

        // Score moyen
        $score_moyen = Projet::where('user_id', $user_id)
            ->whereNotNull('score_qualite')
            ->avg('score_qualite') ?? 0;

        // Alertes de sécurité (erreurs critiques de catégorie "Sécurité")
        $alertes_securite = Erreur::whereHas('analyse.projet', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->whereHas('categorie', function($query) {
            $query->where('nom', 'Sécurité');
        })
        ->where('gravite', 'critique')
        ->count();

        return [
            'total_analyses' => $total_analyses,
            'analyses_semaine' => $analyses_semaine,
            'score_moyen' => $score_moyen,
            'alertes_securite' => $alertes_securite,
        ];
    }
}