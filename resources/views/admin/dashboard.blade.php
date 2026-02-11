<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord  - Analyse de projets
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-blue-500 text-white rounded-lg p-4">
                    <h5 class="text-lg font-bold">Projets</h5>
                    <p class="text-2xl">{{ \App\Models\Projet::count() }}</p>
                </div>
                <div class="bg-green-500 text-white rounded-lg p-4">
                    <h5 class="text-lg font-bold">Analyses</h5>
                    <p class="text-2xl">{{ \App\Models\Analyse::count() }}</p>
                </div>
                <div class="bg-yellow-400 text-white rounded-lg p-4">
                    <h5 class="text-lg font-bold">Erreurs</h5>
                    <p class="text-2xl">{{ \App\Models\Erreur::count() }}</p>
                </div>
                <div class="bg-indigo-500 text-white rounded-lg p-4">
                    <h5 class="text-lg font-bold">Suggestions</h5>
                    <p class="text-2xl">{{ \App\Models\Suggestion::count() }}</p>
                </div>
            </div>

            <!-- Projets récents -->
            <div class="bg-white shadow-sm rounded-lg mb-6 p-4">
                <h3 class="text-lg font-semibold mb-4">Projets récents</h3>
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">Nom du projet</th>
                            <th class="px-4 py-2 border">Type dépôt</th>
                            <th class="px-4 py-2 border">Statut</th>
                            <th class="px-4 py-2 border">Score qualité</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Projet::latest()->take(5)->get() as $projet)
                        <tr>
                            <td class="px-4 py-2 border">{{ $projet->nom }}</td>
                            <td class="px-4 py-2 border">{{ $projet->type_depot }}</td>
                            <td class="px-4 py-2 border">{{ ucfirst(str_replace('_', ' ', $projet->statut)) }}</td>
                            <td class="px-4 py-2 border">{{ $projet->score_qualite ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Dernières analyses -->
            <div class="bg-white shadow-sm rounded-lg p-4">
                <h3 class="text-lg font-semibold mb-4">Dernières analyses</h3>
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2 border">Projet</th>
                            <th class="px-4 py-2 border">Date analyse</th>
                            <th class="px-4 py-2 border">Score qualité</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Analyse::latest()->take(5)->get() as $analyse)
                        <tr>
                            <td class="px-4 py-2 border">{{ $analyse->projet->nom }}</td>
                            <td class="px-4 py-2 border">{{ $analyse->date_analyse }}</td>
                            <td class="px-4 py-2 border">{{ $analyse->score_qualite }}</td>
                            <td class="px-4 py-2 border">
                                <a href="#" class="text-blue-600 hover:underline">Voir</a>
                                <a href="#" class="text-green-600 hover:underline ms-2">PDF</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>
