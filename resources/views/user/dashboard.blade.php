{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-dark">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <button onclick="showTab('dashboard')" class="nav-btn active">
                <i class="bi bi-grid-fill"></i>
                <span>Dashboard</span>
            </button>
            <a href="{{ route('projects.index') }}" class="nav-btn">
                <i class="bi bi-folder-fill"></i>
                <span>Mes Projets</span>
            </a>
            <button onclick="showTab('analysis')" class="nav-btn">
                <i class="bi bi-cpu-fill"></i>
                <span>Analyses IA</span>
            </button>
            <button onclick="showTab('reports')" class="nav-btn">
                <i class="bi bi-file-earmark-text-fill"></i>
                <span>Rapports</span>
            </button>
            <button onclick="showTab('settings')" class="nav-btn">
                <i class="bi bi-gear-fill"></i>
                <span>Paramètres</span>
            </button>

            @if(auth()->user()->role === 'admin')
            <button onclick="showTab('admin')" class="nav-btn">
                <i class="bi bi-shield-fill-check"></i>
                <span>Administration</span>
            </button>
            @endif
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

    <!-- CONTENT -->
    <main class="main-content">
        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab">
            <div class="page-header">
                <div>
                    <h2 class="page-title">Tableau de bord</h2>
                    <p class="page-subtitle">Vue d'ensemble de vos analyses et projets</p>
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

            <!-- Statistiques -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon stat-icon-blue">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Total Analyses</div>
                        <div class="stat-value">{{ $stats['total_analyses'] }}</div>
                        <div class="stat-change stat-change-positive">
                            <i class="bi bi-arrow-up"></i> +{{ $stats['analyses_semaine'] }} cette semaine
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-green">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Score Moyen</div>
                        <div class="stat-value stat-value-accent">
                            {{ $stats['score_moyen'] > 0 ? round($stats['score_moyen']) : 'N/A' }}
                            @if($stats['score_moyen'] > 0)
                            <span>/100</span>
                            @endif
                        </div>
                        <div class="stat-change">Qualité globale du code</div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon stat-icon-red">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <div class="stat-content">
                        <div class="stat-label">Alertes Sécurité</div>
                        <div class="stat-value stat-value-danger">{{ $stats['alertes_securite'] }}</div>
                        <div class="stat-change">Nécessitent une correction</div>
                    </div>
                </div>
            </div>

            <!-- Tableau des projets -->
            <div class="card-modern">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Analyses Récentes</h3>
                        <p class="card-subtitle">Vos derniers projets analysés</p>
                    </div>
                    <a href="{{ route('projects.index') }}" class="btn-secondary">
                        <i class="bi bi-arrow-right"></i>
                        Voir tout
                    </a>
                </div>
                
                @if($projets_recents->count() > 0)
                <div class="table-container">
                    <table class="table-modern">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Date</th>
                                <th>Score</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projets_recents as $projet)
                            <tr>
                                <td>
                                    <div class="project-cell">
                                        <div class="project-icon">
                                            <i class="bi {{ $projet->icone }}"></i>
                                        </div>
                                        <div>
                                            <div class="project-name">{{ $projet->nom }}</div>
                                            <div class="project-meta">
                                                {{ $projet->type_depot }}
                                                @if($projet->analyses->count() > 0)
                                                • {{ $projet->analyses->count() }} analyse(s)
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $projet->created_at->format('d M Y') }}</td>
                                <td>
                                    @if($projet->score_qualite)
                                    <div class="score-badge score-{{ $projet->score_color }}">
                                        <i class="bi bi-{{ $projet->score_qualite >= 80 ? 'check-circle-fill' : ($projet->score_qualite >= 60 ? 'dash-circle-fill' : 'x-circle-fill') }}"></i>
                                        {{ round($projet->score_qualite) }}%
                                    </div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        @if($projet->estAnalyse())
                                        <a href="{{ route('projects.show', $projet->id) }}" class="btn-icon" title="Voir détails">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @else
                                        <form action="{{ route('projects.analyze', $projet->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn-icon" title="Lancer l'analyse">
                                                <i class="bi bi-play-circle"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <button class="btn-icon" onclick="alert('Télécharger rapport - À venir')" title="Télécharger rapport">
                                            <i class="bi bi-download"></i>
                                        </button>
                                        <form action="{{ route('projects.destroy', $projet->id) }}" method="POST" 
                                              style="display: inline;"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-danger" title="Supprimer">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state-inline">
                    <i class="bi bi-inbox"></i>
                    <p>Aucun projet analysé. <a href="{{ route('projects.upload') }}">Importez votre premier projet</a></p>
                </div>
                @endif
            </div>
        </div>

        <!-- Onglet Analyses IA -->
        <div id="analysis" class="tab hidden">
            <div class="page-header">
                <h2 class="page-title">Analyses IA</h2>
            </div>
            <div class="card-modern">
                <div class="card-body">
                    <p class="text-muted">Module d'analyse IA - Sprint 2</p>
                    <p class="text-muted" style="margin-top: 12px;">
                        Cette section affichera les résultats détaillés de l'analyse IA de vos projets.
                    </p>
                </div>
            </div>
        </div>

        <!-- Onglet Rapports -->
        <div id="reports" class="tab hidden">
            <div class="page-header">
                <h2 class="page-title">Rapports</h2>
            </div>
            <div class="card-modern">
                <div class="card-body">
                    <p class="text-muted">Module de génération de rapports PDF - Sprint 3</p>
                </div>
            </div>
        </div>

        <!-- Onglet Paramètres -->
        <div id="settings" class="tab hidden">
            <div class="page-header">
                <h2 class="page-title">Paramètres</h2>
            </div>
            <div class="card-modern">
                <div class="card-body">
                    <p class="text-muted">Paramètres du compte - À venir</p>
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
        <div id="admin" class="tab hidden">
            <div class="page-header">
                <h2 class="page-title">Administration</h2>
            </div>
            <div class="card-modern">
                <div class="card-body">
                    <p class="text-muted">Panel d'administration - À venir</p>
                </div>
            </div>
        </div>
        @endif
    </main>
</div>

{{-- Scripts --}}
<script>
function showTab(id){
    document.querySelectorAll('.tab').forEach(t => t.classList.add('hidden'));
    document.getElementById(id).classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
    event.target.closest('.nav-btn').classList.add('active');
}
</script>

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

.btn-secondary {
    background: transparent;
    color: var(--text-main);
    border: 1px solid var(--border-color);
    padding: 10px 20px;
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
}

/* ALERTS */
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

/* STATS GRID */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
    margin-bottom: 32px;
}

.stat-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    padding: 24px;
    display: flex;
    gap: 20px;
    align-items: flex-start;
    transition: all 0.3s ease;
}

.stat-card:hover {
    border-color: #3f3f46;
    transform: translateY(-2px);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.stat-icon-blue {
    background: rgba(59, 130, 246, 0.15);
    color: var(--primary-accent);
}

.stat-icon-green {
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
}

.stat-icon-red {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger-accent);
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
    margin-bottom: 8px;
}

.stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-main);
    margin-bottom: 4px;
}

.stat-value span {
    font-size: 1.2rem;
    color: var(--text-muted);
    font-weight: 600;
}

.stat-value-accent {
    color: var(--primary-accent);
}

.stat-value-danger {
    color: var(--danger-accent);
}

.stat-change {
    font-size: 0.85rem;
    color: var(--text-muted);
    display: flex;
    align-items: center;
    gap: 4px;
}

.stat-change-positive {
    color: var(--secondary-accent);
}

/* CARD */
.card-modern {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-main);
    margin-bottom: 4px;
}

.card-subtitle {
    font-size: 0.85rem;
    color: var(--text-muted);
}

.card-body {
    padding: 24px;
}

/* TABLE */
.table-container {
    overflow-x: auto;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
}

.table-modern thead tr {
    background: rgba(59, 130, 246, 0.03);
}

.table-modern th {
    padding: 16px 24px;
    text-align: left;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}

.table-modern td {
    padding: 20px 24px;
    border-top: 1px solid var(--border-color);
    color: var(--text-main);
}

.table-modern tbody tr {
    transition: background 0.2s ease;
}

.table-modern tbody tr:hover {
    background: rgba(59, 130, 246, 0.03);
}

.text-muted {
    color: var(--text-muted);
}

.project-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.project-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: rgba(59, 130, 246, 0.1);
    color: var(--primary-accent);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.project-name {
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 2px;
}

.project-meta {
    font-size: 0.8rem;
    color: var(--text-muted);
}

.score-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 0.9rem;
}

.score-high, .score-success {
    background: rgba(16, 185, 129, 0.15);
    color: var(--secondary-accent);
}

.score-medium, .score-warning {
    background: rgba(245, 158, 11, 0.15);
    color: var(--warning-accent);
}

.score-low, .score-danger {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger-accent);
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
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.action-buttons {
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

.btn-icon:hover {
    background: rgba(59, 130, 246, 0.15);
    color: var(--primary-accent);
}

.btn-icon-danger:hover {
    background: rgba(239, 68, 68, 0.15);
    color: var(--danger-accent);
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
}

.empty-state-inline p {
    margin: 0;
    font-size: 1rem;
}

.empty-state-inline a {
    color: var(--primary-accent);
    text-decoration: none;
    font-weight: 600;
}

.empty-state-inline a:hover {
    text-decoration: underline;
}

/* TABS */
.tab {
    display: block;
}

.tab.hidden {
    display: none;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .sidebar {
        width: 240px;
    }

    .main-content {
        padding: 24px 16px;
    }

    .stats-grid {
        grid-template-columns: 1fr;
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