<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSecretaireGeneralRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Si vous utilisez une table roles séparée
        if (Schema::hasTable('roles')) {
            DB::table('roles')->insert([
                'name' => 'secretaire_general',
                'display_name' => 'Secrétaire Général',
                'description' => 'Peut traiter toutes les demandes et gérer l\'administration',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Si vous utilisez un enum dans la table users
        if (Schema::hasColumn('users', 'role')) {
            // Modifier l'enum pour ajouter le nouveau rôle
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('employe', 'responsable_hierarchique', 'drh', 'president', 'secretaire_general') DEFAULT 'employe'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Supprimer le rôle de la table roles si elle existe
        if (Schema::hasTable('roles')) {
            DB::table('roles')->where('name', 'secretaire_general')->delete();
        }
        
        // Remettre l'enum à son état précédent
        if (Schema::hasColumn('users', 'role')) {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('agent', 'responsable_hierarchique', 'drh', 'president','admin') DEFAULT 'agent'");
        }
    }
}