<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SgSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Créer un utilisateur secrétaire général
        User::create([
            'nom' => 'OUEDRAOGO',
            'prenom' => 'Marie',
            'email' => 'secretaire@uts.bf',
            'matricule' => 'SG001',
            'telephone' => '70123456',
            'password' => Hash::make('motdepassesg'),
            'role' => 'secretaire_general',
            'direction_id' => 1, // Ajustez selon votre structure
            'statut' => 'actif',
            'date_embauche' => '2020-01-15',
            
            // Quotas de congés (généralement plus élevés pour les cadres)
            'conge_annuel_total' => 30,
            'conge_annuel_restant' => 30,
            'absence_totale' => 10,
            'absence_restante' => 10,
            'maternite_total' => 98,
            'maternite_restant' => 98,
            
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        echo "Utilisateur secrétaire général créé avec succès!\n";
        echo "Email: secretaire.general@institution.bf\n";
        echo "Mot de passe: password123\n";
    }
}