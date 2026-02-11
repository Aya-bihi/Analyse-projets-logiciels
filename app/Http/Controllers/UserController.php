<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Projet;
use App\Models\Analyse;
use App\Models\Erreur;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Projets récents
        $projets_recents = Projet::where('user_id', $user->id)
            ->with(['analyses.erreurs.categorie'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Statistiques
        $stats = $this->calculerStatistiques($user->id);

        return view('user.dashboard', compact('projets_recents', 'stats'));
    }

    private function calculerStatistiques($user_id)
    {
        $total_analyses = Analyse::whereHas('projet', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->count();

        $analyses_semaine = Analyse::whereHas('projet', function($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })
        ->where('created_at', '>=', Carbon::now()->subWeek())
        ->count();

        $score_moyen = Projet::where('user_id', $user_id)
            ->whereNotNull('score_qualite')
            ->avg('score_qualite') ?? 0;

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