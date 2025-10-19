<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    
    /**
     * Seed the application's database.
     */
   public function run()
{
    // Paramètres par défaut
    \App\Models\Parametre::create([
        'code' => 'jours_conges_annuels',
        'valeur' => '25',
        'type' => 'integer',
        'description' => 'Nombre de jours de congés annuels'
    ]);

    // Rôles Spatie
    $admin = \Spatie\Permission\Models\Role::create(['name' => 'admin']);
    $user = \Spatie\Permission\Models\Role::create(['name' => 'user']);

    // Admin par défaut
    \App\Models\User::create([
        'matricule' => 'ADMIN001',
        'nom' => 'Admin',
        'prenom' => 'System',
        'email' => 'admin@example.com',
        'password' => bcrypt('password'),
    ])->assignRole('admin');
}
}
