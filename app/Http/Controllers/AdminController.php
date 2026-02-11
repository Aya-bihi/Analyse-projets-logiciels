<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Projet;
use App\Models\Analyse;

class AdminController extends Controller
{
    /**
     * Dashboard de l'administrateur
     */
    public function index()
    {
        // Nombre d'utilisateurs
        $usersCount = User::where('role', 'user')->count();

        // Nombre de projets
        $projetsCount = Projet::count();

        // Nombre d'analyses
        $analysesCount = Analyse::count();

        return view('admin.dashboard', compact('usersCount', 'projetsCount', 'analysesCount'));
    }
}
