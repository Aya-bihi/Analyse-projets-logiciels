{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen flex bg-gray-900 text-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-gray-800 border-r border-gray-700 p-6 flex flex-col">
        <h1 class="text-2xl font-bold mb-8 text-white">Analyse<span class="text-blue-500">Projets</span></h1>
        
        <nav class="flex-1 flex flex-col gap-2">
            <button onclick="showTab('dashboard')" class="nav-btn active">📊 Dashboard</button>
            <button onclick="showTab('projects')" class="nav-btn">📁 Mes Projets</button>
            <button onclick="showTab('analysis')" class="nav-btn">🛠 Analyses IA</button>
            <button onclick="showTab('reports')" class="nav-btn">📄 Rapports</button>
            <button onclick="showTab('settings')" class="nav-btn">⚙️ Paramètres</button>

            @if(auth()->user()->role === 'admin')
            <button onclick="showTab('admin')" class="nav-btn">👑 Administration</button>
            @endif
        </nav>

        <form method="POST" action="{{ route('logout') }}" class="mt-auto pt-4">
            @csrf
            <button type="submit" class="w-full text-left hover:text-red-500">🚪 Déconnexion</button>
        </form>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-8 overflow-auto">
        <!-- Dashboard Tab -->
        <div id="dashboard" class="tab">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold">Tableau de bord</h2>
                <button class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded text-white" onclick="alert('Ajouter un projet');">➕ Nouveau Projet</button>
            </div>

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-800 p-6 rounded-xl shadow">
                    <h3 class="text-gray-400 uppercase text-sm font-bold mb-2">Total Analyses</h3>
                    <div class="text-2xl font-bold">24</div>
                    <div class="text-green-400 text-sm mt-1">+3 cette semaine</div>
                </div>
                <div class="bg-gray-800 p-6 rounded-xl shadow">
                    <h3 class="text-gray-400 uppercase text-sm font-bold mb-2">Score Moyen</h3>
                    <div class="text-2xl font-bold text-blue-500">84 / 100</div>
                    <div class="text-gray-400 text-sm mt-1">Qualité globale du code</div>
                </div>
                <div class="bg-gray-800 p-6 rounded-xl shadow">
                    <h3 class="text-gray-400 uppercase text-sm font-bold mb-2">Alertes Sécurité</h3>
                    <div class="text-2xl font-bold text-red-500">12</div>
                    <div class="text-gray-400 text-sm mt-1">Nécessitent une correction</div>
                </div>
            </div>

            <!-- Tableau des projets -->
            <div class="bg-gray-800 rounded-xl overflow-hidden">
                <div class="flex justify-between p-4 border-b border-gray-700">
                    <h3 class="font-bold">Analyses Récentes</h3>
                    <button class="bg-transparent border border-gray-600 text-gray-100 px-3 py-1 rounded hover:bg-gray-700">Voir tout</button>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-gray-900">
                        <tr>
                            <th class="p-3 text-gray-400">Projet</th>
                            <th class="p-3 text-gray-400">Date</th>
                            <th class="p-3 text-gray-400">Score</th>
                            <th class="p-3 text-gray-400">Statut</th>
                            <th class="p-3 text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-gray-700">
                            <td class="p-3 font-semibold">E-commerce_API.zip</td>
                            <td class="p-3 text-gray-400">25 Janv 2026</td>
                            <td class="p-3 font-bold text-green-400">92%</td>
                            <td class="p-3"><span class="px-3 py-1 rounded-full bg-green-900 text-green-400 text-sm">Terminé</span></td>
                            <td class="p-3 flex gap-2">
                                <button class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600" onclick="alert('Voir projet')">👁</button>
                                <button class="px-3 py-1 bg-red-700 rounded hover:bg-red-600" onclick="alert('Supprimer projet')">🗑</button>
                            </td>
                        </tr>
                        <tr class="border-t border-gray-700">
                            <td class="p-3 font-semibold">ML_Model_Python</td>
                            <td class="p-3 text-gray-400">24 Janv 2026</td>
                            <td class="p-3 font-bold text-yellow-400">78%</td>
                            <td class="p-3"><span class="px-3 py-1 rounded-full bg-yellow-900 text-yellow-400 text-sm">En cours</span></td>
                            <td class="p-3 flex gap-2">
                                <button class="px-3 py-1 bg-gray-700 rounded hover:bg-gray-600" onclick="alert('Voir projet')">👁</button>
                                <button class="px-3 py-1 bg-red-700 rounded hover:bg-red-600" onclick="alert('Supprimer projet')">🗑</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Autres onglets -->
        <div id="projects" class="tab hidden">Mes Projets</div>
        <div id="analysis" class="tab hidden">Analyses IA</div>
        <div id="reports" class="tab hidden">Rapports</div>
        <div id="settings" class="tab hidden">Paramètres</div>
        @if(auth()->user()->role === 'admin')
        <div id="admin" class="tab hidden">Administration</div>
        @endif
    </main>
</div>

{{-- Scripts pour les tabs --}}
<script>
function showTab(id){
    document.querySelectorAll('.tab').forEach(t => t.classList.add('hidden'));
    document.getElementById(id).classList.remove('hidden');

    document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
    document.querySelectorAll('.nav-btn').forEach(btn => {
        if(btn.textContent.trim().includes(id.charAt(0).toUpperCase() + id.slice(1))) btn.classList.add('active');
    });
}
</script>

{{-- Styles --}}
<style>
.nav-btn{
    padding: 10px 14px;
    border-radius: 8px;
    text-align: left;
    font-weight: 500;
    color: #a1a1aa;
    cursor: pointer;
    transition: all 0.2s;
}
.nav-btn:hover, .nav-btn.active{
    color: #3b82f6;
    background: rgba(59,130,246,0.1);
}
.tab{ display:block; }
.tab.hidden{ display:none; }
</style>
@endsection
