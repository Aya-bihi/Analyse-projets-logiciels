<?php
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {

    // Route Dashboard qui redirige selon le rôle
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    })->name('dashboard');

    // Dashboard admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // Dashboard user
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('user.dashboard');
    

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes des projets
    Route::get('/projects/upload', [ProjetController::class, 'showUploadForm'])->name('projects.upload');
    Route::post('/projects/store', [ProjetController::class, 'store'])->name('projects.store');

    // Liste et gestion des projets
    Route::get('/projects', [ProjetController::class, 'index'])->name('projects.index');
    Route::get('/projects/{id}', [ProjetController::class, 'show'])->name('projects.show');
    Route::delete('/projects/{id}', [ProjetController::class, 'destroy'])->name('projects.destroy');
    
    // Lancer l'analyse d'un projet
    Route::post('/projects/{id}/analyze', [ProjetController::class, 'lancerAnalyse'])->name('projects.analyze');
});

require __DIR__.'/auth.php';