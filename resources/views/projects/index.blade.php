{{-- resources/views/projects/index.blade.php --}}
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
            <a href="{{ route('projects.upload') }}" class="nav-btn">
                <i class="bi bi-cloud-upload-fill"></i>
                <span>Nouveau Projet</span>
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
                <h2 class="page-title">Mes Projets</h2>
                <p class="page-subtitle">{{ $projets->count() }} projet(s) importé(s)</p>
            </div>
            <a href="{{ route('projects.upload') }}" class="btn-primary">
                <i class="bi bi-plus-circle"></i>
                Nouveau Projet
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if($projets->count() > 0)
        <div class="projects-grid">
            @foreach($projets as $projet)
            <div class="project-card">
                <div class="project-card-header">
                    <div class="project-type-icon">
                        <i class="bi {{ $projet->icone }}"></i>
                    </div>
                    <div class="project-card-actions">
                        <form action="{{ route('projects.destroy', $projet->id) }}" method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="project-card-body">
                    <h3 class="project-card-title">{{ $projet->nom }}</h3>
                    <p class="project-card-meta">
                        <i class="bi bi-calendar3"></i>
                        {{ $projet->created_at->diffForHumans() }}
                    </p>

                    @if($projet->estAnalyse())
                    <div class="project-score">
                        <div class="score-circle score-{{ $projet->score_color }}">
                            <span class="score-value">{{ round($projet->score_qualite) }}</span>
                        </div>
                        <div class="score-label">Score de qualité</div>
                    </div>
                    @endif

                    <div class="project-stats">
                        <div class="stat-item">
                            <i class="bi bi-bar-chart-fill"></i>
                            <span>{{ $projet->analyses->count() }} analyse(s)</span>
                        </div>
                        @if($projet->estAnalyse())
                        <div class="stat-item">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>{{ $projet->total_erreurs }} erreur(s)</span>
                        </div>
                        @endif
                    </div>

                    <div class="project-status">
                        @if($projet->statut === 'analyse_terminee')
                        <span class="status-badge status-success">
                            <span class="status-dot"></span>
                            Terminé
                        </span>
                        @elseif($projet->statut === 'analyse_en_cours')
                        <span class="status-badge status-warning">
                            <span class="status-dot"></span>
                            En cours
                        </span>
                        @else
                        <span class="status-badge status-pending">
                            <span class="status-dot"></span>
                            En attente
                        </span>
                        @endif
                    </div>
                </div>

                <div class="project-card-footer">
                    @if($projet->estAnalyse())
                    <a href="{{ route('projects.show', $projet->id) }}" class="btn-card-primary">
                        <i class="bi bi-eye"></i>
                        Voir les résultats
                    </a>
                    @else
                    <form action="{{ route('projects.analyze', $projet->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-card-secondary">
                            <i class="bi bi-play-circle"></i>
                            Lancer l'analyse
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="bi bi-folder-x"></i>
            </div>
            <h3 class="empty-state-title">Aucun projet importé</h3>
            <p class="empty-state-text">Commencez par importer votre premier projet pour l'analyser</p>
            <a href="{{ route('projects.upload') }}" class="btn-primary">
                <i class="bi bi-plus-circle"></i>
                Importer un projet
            </a>
        </div>
        @endif
    </main>
</div>

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

.bg-dark { background-color: var(--bg-dark); }

/* SIDEBAR - Même style que les autres pages */
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

.user-info { flex: 1; min-width: 0; }

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

.btn-primary {
    background: var(--text-main);
    color: var(--bg-dark);
    border: none;
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}

.btn-primary:hover {
    background: #e4e4e7;
    transform: translateY(-2px);
}

/* ALERT */
.alert-success {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
    border: 1px solid rgba(16, 185, 129, 0.3);
}

/* PROJECTS GRID */
.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 24px;
}

.project-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.project-card:hover {
    border-color: var(--primary-accent);
    transform: translateY(-4px);
}

.project-card-header {
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
}

.project-type-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.project-card-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(255, 255, 255, 0.05);
    color: var(--text-muted);
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-icon-danger:hover {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger-accent);
}

.project-card-body {
    padding: 20px;
}

.project-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 8px;
}

.project-card-meta {
    color: var(--text-muted);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 16px;
}

.project-score {
    text-align: center;
    margin: 20px 0;
}

.score-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
    position: relative;
}

.score-circle.score-success {
    background: rgba(16, 185, 129, 0.15);
    border: 3px solid var(--secondary-accent);
}

.score-circle.score-warning {
    background: rgba(245, 158, 11, 0.15);
    border: 3px solid var(--warning-accent);
}

.score-circle.score-danger {
    background: rgba(239, 68, 68, 0.15);
    border: 3px solid var(--danger-accent);
}

.score-value {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-main);
}

.score-label {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.project-stats {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin: 16px 0;
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.stat-item i {
    color: var(--primary-accent);
}

.project-status {
    margin-top: 16px;
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.status-success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
}

.status-success .status-dot {
    background: var(--secondary-accent);
}

.status-warning {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning-accent);
}

.status-warning .status-dot {
    background: var(--warning-accent);
}

.status-pending {
    background: rgba(161, 161, 170, 0.15);
    color: var(--text-muted);
}

.status-pending .status-dot {
    background: var(--text-muted);
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.project-card-footer {
    padding: 20px;
    border-top: 1px solid var(--border-color);
}

.btn-card-primary,
.btn-card-secondary {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: none;
    text-decoration: none;
}

.btn-card-primary {
    background: var(--primary-accent);
    color: white;
}

.btn-card-primary:hover {
    background: #2563eb;
}

.btn-card-secondary {
    background: transparent;
    color: var(--text-main);
    border: 1px solid var(--border-color);
}

.btn-card-secondary:hover {
    background: rgba(59, 130, 246, 0.1);
    border-color: var(--primary-accent);
}

/* EMPTY STATE */
.empty-state {
    text-align: center;
    padding: 80px 20px;
}

.empty-state-icon {
    font-size: 5rem;
    color: var(--text-muted);
    margin-bottom: 24px;
    opacity: 0.5;
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 12px;
}

.empty-state-text {
    color: var(--text-muted);
    font-size: 1rem;
    margin-bottom: 32px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .projects-grid {
        grid-template-columns: 1fr;
    }

    .main-content {
        padding: 24px 16px;
    }

    .page-header {
        flex-direction: column;
        gap: 16px;
    }

    .btn-primary {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endsection