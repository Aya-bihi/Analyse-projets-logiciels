<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Projet;
use App\Models\Analyse;
use App\Models\Erreur;
use App\Models\ErreurCategorie;
use App\Models\Suggestion;
use Carbon\Carbon;

class ProjetTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Ce seeder génère des données de test pour visualiser le dashboard
     */
    public function run(): void
    {
        // Récupérer les catégories d'erreurs
        $categories = ErreurCategorie::all();
        
        if ($categories->isEmpty()) {
            $this->command->error('Veuillez d\'abord exécuter le ErreurCategorieSeeder');
            return;
        }

        // Récupérer les utilisateurs
        $users = \App\Models\User::all();

        if ($users->isEmpty()) {
            $this->command->error('Aucun utilisateur trouvé dans la base de données');
            return;
        }

        foreach ($users as $user) {
            // Projet 1 : E-commerce API (terminé, bon score)
            $projet1 = Projet::create([
                'user_id' => $user->id,
                'nom' => 'E-commerce_API.zip',
                'type_depot' => 'ZIP',
                'lien_depot' => 'projects/ecommerce_' . time(),
                'statut' => 'analyse_terminee',
                'score_qualite' => 92,
                'created_at' => Carbon::now()->subDays(2),
            ]);

            $analyse1 = Analyse::create([
                'projet_id' => $projet1->id,
                'score_qualite' => 92,
                'date_analyse' => Carbon::now()->subDays(2),
            ]);

            // Quelques erreurs mineures
            $this->createErreurs($analyse1, $categories, [
                ['categorie' => 'Bonnes pratiques', 'gravite' => 'faible', 'nb' => 3],
                ['categorie' => 'Performance', 'gravite' => 'moyenne', 'nb' => 1],
            ]);

            // Projet 2 : ML Model Python (en cours, score moyen)
            $projet2 = Projet::create([
                'user_id' => $user->id,
                'nom' => 'ML_Model_Python',
                'type_depot' => 'GitHub',
                'lien_depot' => 'https://github.com/user/ml-model',
                'statut' => 'analyse_terminee',
                'score_qualite' => 78,
                'created_at' => Carbon::now()->subDays(1),
            ]);

            $analyse2 = Analyse::create([
                'projet_id' => $projet2->id,
                'score_qualite' => 78,
                'date_analyse' => Carbon::now()->subDays(1),
            ]);

            $this->createErreurs($analyse2, $categories, [
                ['categorie' => 'Syntaxe', 'gravite' => 'faible', 'nb' => 2],
                ['categorie' => 'Performance', 'gravite' => 'moyenne', 'nb' => 3],
                ['categorie' => 'Sécurité', 'gravite' => 'moyenne', 'nb' => 1],
                ['categorie' => 'Bonnes pratiques', 'gravite' => 'faible', 'nb' => 5],
            ]);

            // Projet 3 : Portfolio React (terminé, bon score)
            $projet3 = Projet::create([
                'user_id' => $user->id,
                'nom' => 'Portfolio_ReactJS',
                'type_depot' => 'GitHub',
                'lien_depot' => 'https://github.com/user/portfolio',
                'statut' => 'analyse_terminee',
                'score_qualite' => 88,
                'created_at' => Carbon::now()->subDays(3),
            ]);

            $analyse3 = Analyse::create([
                'projet_id' => $projet3->id,
                'score_qualite' => 88,
                'date_analyse' => Carbon::now()->subDays(3),
            ]);

            $this->createErreurs($analyse3, $categories, [
                ['categorie' => 'Performance', 'gravite' => 'faible', 'nb' => 2],
                ['categorie' => 'Bonnes pratiques', 'gravite' => 'faible', 'nb' => 3],
            ]);

            // Projet 4 : Legacy Code (mauvais score, beaucoup d'erreurs)
            $projet4 = Projet::create([
                'user_id' => $user->id,
                'nom' => 'Legacy_PHP_Project',
                'type_depot' => 'ZIP',
                'lien_depot' => 'projects/legacy_' . time(),
                'statut' => 'analyse_terminee',
                'score_qualite' => 45,
                'created_at' => Carbon::now()->subDays(5),
            ]);

            $analyse4 = Analyse::create([
                'projet_id' => $projet4->id,
                'score_qualite' => 45,
                'date_analyse' => Carbon::now()->subDays(5),
            ]);

            $this->createErreurs($analyse4, $categories, [
                ['categorie' => 'Syntaxe', 'gravite' => 'critique', 'nb' => 5],
                ['categorie' => 'Sécurité', 'gravite' => 'critique', 'nb' => 7],
                ['categorie' => 'Logique', 'gravite' => 'moyenne', 'nb' => 8],
                ['categorie' => 'Performance', 'gravite' => 'moyenne', 'nb' => 10],
                ['categorie' => 'Bonnes pratiques', 'gravite' => 'faible', 'nb' => 15],
            ]);

            // Projet 5 : En attente d'analyse
            Projet::create([
                'user_id' => $user->id,
                'nom' => 'Mobile_App_Flutter',
                'type_depot' => 'ZIP',
                'lien_depot' => 'projects/flutter_' . time(),
                'statut' => 'en_attente',
                'score_qualite' => null,
                'created_at' => Carbon::now(),
            ]);
        }

        $this->command->info('✅ Données de test créées avec succès !');
    }

    /**
     * Créer des erreurs pour une analyse
     */
    private function createErreurs($analyse, $categories, $erreurs_config)
    {
        $fichiers = [
            'src/Controller/UserController.php',
            'src/Model/User.php',
            'src/Service/AuthService.php',
            'public/index.php',
            'config/database.php',
            'app/Http/Controllers/ProductController.php',
            'resources/views/home.blade.php',
        ];

        foreach ($erreurs_config as $config) {
            $categorie = $categories->firstWhere('nom', $config['categorie']);
            
            if (!$categorie) continue;

            for ($i = 0; $i < $config['nb']; $i++) {
                $erreur = Erreur::create([
                    'analyse_id' => $analyse->id,
                    'categorie_id' => $categorie->id,
                    'fichier' => $fichiers[array_rand($fichiers)],
                    'ligne' => rand(10, 500),
                    'description' => $this->getDescriptionErreur($config['categorie'], $config['gravite']),
                    'gravite' => $config['gravite'],
                ]);

                // Ajouter une suggestion
                Suggestion::create([
                    'erreur_id' => $erreur->id,
                    'suggestion' => $this->getSuggestion($config['categorie']),
                ]);
            }
        }
    }

    /**
     * Générer une description d'erreur réaliste
     */
    private function getDescriptionErreur($categorie, $gravite)
    {
        $descriptions = [
            'Syntaxe' => [
                'critique' => 'Parenthèse fermante manquante',
                'moyenne' => 'Point-virgule manquant en fin de ligne',
                'faible' => 'Espace manquant après la virgule',
            ],
            'Logique' => [
                'critique' => 'Boucle infinie détectée',
                'moyenne' => 'Condition toujours vraie',
                'faible' => 'Variable non utilisée',
            ],
            'Performance' => [
                'critique' => 'Requête N+1 détectée',
                'moyenne' => 'Boucle inefficace, utiliser array_map()',
                'faible' => 'Variable globale utilisée',
            ],
            'Sécurité' => [
                'critique' => 'Faille SQL Injection détectée',
                'moyenne' => 'Validation des entrées utilisateur manquante',
                'faible' => 'Token CSRF non vérifié',
            ],
            'Bonnes pratiques' => [
                'critique' => 'Code dupliqué sur plus de 50 lignes',
                'moyenne' => 'Fonction trop longue (>100 lignes)',
                'faible' => 'Nom de variable non descriptif',
            ],
        ];

        return $descriptions[$categorie][$gravite] ?? 'Erreur détectée';
    }

    /**
     * Générer une suggestion de correction
     */
    private function getSuggestion($categorie)
    {
        $suggestions = [
            'Syntaxe' => 'Vérifiez la syntaxe et ajoutez les éléments manquants',
            'Logique' => 'Revoyez la logique de votre code et testez les cas limites',
            'Performance' => 'Optimisez votre code en utilisant des fonctions natives plus performantes',
            'Sécurité' => 'Utilisez des requêtes préparées et validez toutes les entrées utilisateur',
            'Bonnes pratiques' => 'Suivez les conventions de nommage et structurez mieux votre code',
        ];

        return $suggestions[$categorie] ?? 'Corrigez cette erreur';
    }
}