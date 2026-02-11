<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ErreurCategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'nom' => 'Syntaxe',
                'description' => 'Erreurs de syntaxe du code (parenthèses manquantes, points-virgules, etc.)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Logique',
                'description' => 'Erreurs de logique et bugs potentiels dans le code',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Performance',
                'description' => 'Problèmes de performance et optimisation du code',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Sécurité',
                'description' => 'Failles de sécurité et vulnérabilités potentielles',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nom' => 'Bonnes pratiques',
                'description' => 'Non-respect des conventions et bonnes pratiques de codage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('erreur_categories')->insert($categories);
    }
}