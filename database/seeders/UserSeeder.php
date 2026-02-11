<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Utilisateur normal
        User::create([
            'name' => 'Aya Bihi',
            'email' => 'ayayay72662@gmail.com',
            'password' => Hash::make('12345678'), // mot de passe simple pour test
            'role' => 'user',
        ]);

        // Admin
        User::create([
            'name' => 'Missan El Amrani',
            'email' => 'mayssan19amrani15@gmail.com',
            'password' => Hash::make('123456789'), // mot de passe simple pour test
            'role' => 'admin',
        ]);
    }
}
