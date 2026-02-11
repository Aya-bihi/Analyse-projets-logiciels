{{-- resources/views/projects/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-dark">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <a href="{{ route('user.dashboard') }}" class="nav-btn">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('projects.index') }}" class="nav-btn active">
                <i class="bi bi-folder-fill"></i>
                <span>Mes Projets</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <div class="page-header">
            <div>
                <h2 class="page-title">Projet : {{ $projet->nom }}</h2>
                <p class="page-subtitle">Détails de l'analyse IA et résultats</p>
            </div>
        </div>

        <!-- Messages succès / erreur -->
        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            {{ session('error') }}
        </div>
        @endif

        <!-- Informations du projet -->
        <div class="card-modern mb-6">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="bi bi-info-circle-fill"></i>
                        Informations du projet
                    </h3>
                </div>
            </div>
            <div class="card-body">
                <div class="project-info-grid">
                    <div class="info-item">
                        <span class="info-label">Type de dépôt</span>
                        <span class="info-value">{{ $projet->type_depot }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Statut</span>
                        <span class="info-value">
                            <span class="status-badge status-{{ $projet->statut }}">
                                {{ ucfirst(str_replace('_', ' ', $projet->statut)) }}
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Score qualité global</span>
                        <span class="info-value score-value">
                            {{ $projet->score_qualite ?? 'N/A' }}
                            @if($projet->score_qualite)
                                <span>/100</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique des analyses -->
        @if($projet->analyses->isNotEmpty())
        <div class="card-modern mb-6">
            <div class="card-header">
                <div>
                    <h3 class="card-title">
                        <i class="bi bi-clock-history"></i>
                        Historique des analyses
                    </h3>
                    <p class="card-subtitle">{{ count($projet->analyses) }} analyse(s) effectuée(s)</p>
                </div>
            </div>
            <div class="card-body">
                @foreach($projet->analyses as $index => $analyse)
                    <div class="analyse-card">
                        <!-- En-tête de l'analyse -->
                        <div class="analyse-header">
                            <div class="analyse-number">#{{ $index + 1 }}</div>
                            <div class="analyse-info">
                                <h4 class="analyse-title">
                                    Analyse du {{ \Carbon\Carbon::parse($analyse->date_analyse)->format('d/m/Y à H:i') }}
                                </h4>
                                <p class="analyse-date">
                                    <i class="bi bi-clock"></i>
                                    Il y a {{ \Carbon\Carbon::parse($analyse->date_analyse)->diffForHumans() }}
                                </p>
                            </div>
                            <div class="analyse-score-wrapper">
                                <div class="score-display">
                                    <div class="score-main">{{ $analyse->score_qualite ?? 'N/A' }}<span>/100</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Erreurs -->
                        <div class="analyse-content">
                            <div class="errors-section-header">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                <span>Détails des erreurs</span>
                                <span class="errors-count">{{ count($analyse->erreurs) }} problème(s) détecté(s)</span>
                            </div>

                            @if($analyse->erreurs->isEmpty())
                                <div class="empty-state-inline">
                                    <i class="bi bi-check-circle"></i>
                                    <p>Aucune erreur détectée. Excellent travail !</p>
                                </div>
                            @else
                                <div class="errors-grid">
                                    @foreach($analyse->erreurs as $erreur)
                                    <div class="error-card">
                                        <div class="error-header">
                                            <div class="error-fichier">
                                                <i class="bi bi-file-code-fill"></i>
                                                <span>{{ basename($erreur->fichier) }}</span>
                                            </div>
                                            @if(isset($erreur->gravite))
                                                <span class="severity-badge severity-{{ $erreur->gravite }}">
                                                    {{ ucfirst($erreur->gravite) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="error-location">
                                            Ligne {{ $erreur->ligne }}
                                        </div>
                                        <p class="error-desc">{{ $erreur->description }}</p>
                                        
                                        @if($erreur->suggestions && (is_object($erreur->suggestions) ? $erreur->suggestions->suggestion ?? false : $erreur->suggestions->isNotEmpty()))
                                            <div class="suggestions-wrapper">
                                                <div class="suggestions-header">
                                                    <i class="bi bi-lightbulb-fill"></i>
                                                    <span>Suggestions</span>
                                                </div>
                                                <ul class="suggestions-list">
                                                    @if(is_object($erreur->suggestions) && isset($erreur->suggestions->suggestion))
                                                        <li>{{ $erreur->suggestions->suggestion }}</li>
                                                    @else
                                                        @foreach($erreur->suggestions as $sugg)
                                                            <li>{{ is_object($sugg) ? $sugg->suggestion : $sugg }}</li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @else
            <div class="alert alert-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Ce projet n'a pas encore été analysé.
            </div>
        @endif

        <div class="form-actions">
            <a href="{{ route('projects.index') }}" class="btn-secondary">
                <i class="bi bi-arrow-left-circle"></i>
                Retour aux projets
            </a>
        </div>

    </main>
</div>

{{-- Styles --}}
<style>
:root {
    --bg-dark: #0a0a0c;
    --card-bg: #131316;
    --primary-accent: #3b82f6;
    --secondary-accent: #10b981;
    --danger-accent: #ef4444;
    --warning-accent: #f59e0b;
    --text-main: #f4f4f5;
    --text-muted: #a1a1aa;
    --border-color: #27272a;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.bg-dark {
    background-color: var(--bg-dark);
}

/* SIDEBAR */
.sidebar {
    width: 280px;
    background: var(--card-bg);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    height: 100vh;
    position: sticky;
    top: 0;
}

.sidebar-nav {
    flex: 1;
    padding: 24px 16px;
    overflow-y: auto;
}

.nav-btn {
    width: 100%;
    padding: 12px 16px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 10px;
    font-size: 0.95rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 4px;
    text-decoration: none;
}

.nav-btn i {
    font-size: 1.1rem;
}

.nav-btn:hover {
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-accent);
}

.nav-btn.active {
    background: rgba(59, 130, 246, 0.15);
    color: var(--primary-accent);
}

.sidebar-footer {
    padding: 16px;
    border-top: 1px solid var(--border-color);
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: rgba(59, 130, 246, 0.05);
    border-radius: 10px;
    margin-bottom: 12px;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-accent);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    text-transform: uppercase;
}

.user-info {
    flex: 1;
    min-width: 0;
}

.user-name {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-main);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-email {
    font-size: 0.75rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.btn-logout {
    width: 100%;
    padding: 10px 16px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: 8px;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-logout:hover {
    background: rgba(239, 68, 68, 0.1);
    color: var(--danger-accent);
}

/* MAIN CONTENT */
.main-content {
    flex: 1;
    padding: 40px;
    overflow-y: auto;
    max-width: 100%;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 32px;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 4px;
}

.page-subtitle {
    color: var(--text-muted);
    font-size: 0.95rem;
}

/* ALERTS */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger-accent);
    border: 1px solid rgba(239, 68, 68, 0.3);
}

/* CARD */
.card-modern {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.mb-6 {
    margin-bottom: 24px;
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-title i {
    color: var(--primary-accent);
}

.card-subtitle {
    font-size: 0.85rem;
    color: var(--text-muted);
    margin-top: 4px;
}

.card-body {
    padding: 24px;
}

/* PROJECT INFO GRID */
.project-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 24px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-label {
    color: var(--text-muted);
    font-size: 0.85rem;
    font-weight: 500;
}

.info-value {
    color: var(--text-main);
    font-size: 1rem;
    font-weight: 600;
}

.score-value {
    font-size: 1.5rem;
    color: var(--primary-accent);
}

.score-value span {
    font-size: 1rem;
    color: var(--text-muted);
}

.status-badge {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: capitalize;
}

.status-badge.status-actif {
    background: rgba(16, 185, 129, 0.2);
    color: var(--secondary-accent);
    border: 1px solid rgba(16, 185, 129, 0.4);
}

.status-badge.status-en_cours {
    background: rgba(245, 158, 11, 0.2);
    color: var(--warning-accent);
    border: 1px solid rgba(245, 158, 11, 0.4);
}

.status-badge.status-termine {
    background: rgba(59, 130, 246, 0.2);
    color: var(--primary-accent);
    border: 1px solid rgba(59, 130, 246, 0.4);
}

/* ANALYSE CARD */
.analyse-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    margin-bottom: 24px;
    overflow: hidden;
}

.analyse-header {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: rgba(59, 130, 246, 0.05);
    border-bottom: 1px solid var(--border-color);
}

.analyse-number {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: var(--primary-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.analyse-info {
    flex: 1;
}

.analyse-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0 0 4px 0;
}

.analyse-date {
    font-size: 0.85rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0;
}

.analyse-score-wrapper {
    text-align: center;
}

.score-display {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.score-main {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary-accent);
}

.score-main span {
    font-size: 1rem;
    color: var(--text-muted);
    font-weight: 600;
}

/* ANALYSE CONTENT */
.analyse-content {
    padding: 20px;
}

.errors-section-header {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-main);
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 16px;
}

.errors-section-header i {
    color: var(--primary-accent);
}

.errors-count {
    color: var(--text-muted);
    font-size: 0.85rem;
    font-weight: 400;
    margin-left: auto;
}

/* ERRORS GRID */
.errors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 20px;
}

.error-card {
    background: rgba(255, 255, 255, 0.02);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 20px;
    transition: all 0.3s ease;
}

.error-card:hover {
    border-color: var(--primary-accent);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(59, 130, 246, 0.1);
}

.error-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
    gap: 12px;
}

.error-fichier {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-main);
    font-size: 0.9rem;
    flex: 1;
    min-width: 0;
}

.error-fichier i {
    color: var(--primary-accent);
    font-size: 1rem;
    flex-shrink: 0;
}

.error-fichier span {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.error-location {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-bottom: 8px;
}

.error-desc {
    color: var(--text-main);
    margin-bottom: 16px;
    font-size: 0.9rem;
    line-height: 1.5;
}

.severity-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    flex-shrink: 0;
}

.severity-critique {
    background: rgba(239, 68, 68, 0.2);
    color: var(--danger-accent);
    border: 1px solid rgba(239, 68, 68, 0.4);
}

.severity-majeure {
    background: rgba(245, 158, 11, 0.2);
    color: var(--warning-accent);
    border: 1px solid rgba(245, 158, 11, 0.4);
}

.severity-mineure {
    background: rgba(16, 185, 129, 0.2);
    color: var(--secondary-accent);
    border: 1px solid rgba(16, 185, 129, 0.4);
}

.suggestions-wrapper {
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid var(--border-color);
}

.suggestions-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--text-main);
    font-size: 0.9rem;
}

.suggestions-header i {
    color: var(--warning-accent);
}

.suggestions-list {
    list-style-type: none;
    padding-left: 0;
    margin: 0;
}

.suggestions-list li {
    color: var(--text-muted);
    margin-bottom: 8px;
    padding-left: 20px;
    position: relative;
    font-size: 0.85rem;
    line-height: 1.5;
}

.suggestions-list li:before {
    content: "→";
    position: absolute;
    left: 0;
    color: var(--primary-accent);
    font-weight: bold;
}

/* EMPTY STATE */
.empty-state-inline {
    padding: 60px 24px;
    text-align: center;
    color: var(--text-muted);
}

.empty-state-inline i {
    font-size: 3rem;
    margin-bottom: 16px;
    opacity: 0.5;
    color: var(--secondary-accent);
}

.empty-state-inline p {
    margin: 0;
    font-size: 1rem;
}

/* BUTTONS */
.form-actions {
    margin-top: 32px;
    display: flex;
    gap: 12px;
}

.btn-secondary {
    background: transparent;
    color: var(--text-main);
    border: 1px solid var(--border-color);
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 500;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-secondary:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: #52525b;
    transform: translateY(-2px);
}

.text-muted {
    color: var(--text-muted);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .sidebar {
        width: 240px;
    }

    .main-content {
        padding: 24px 16px;
    }

    .errors-grid {
        grid-template-columns: 1fr;
    }

    .page-header {
        flex-direction: column;
        gap: 16px;
    }
    
    .project-info-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection