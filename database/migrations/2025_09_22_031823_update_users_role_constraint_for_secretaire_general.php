<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateUsersRoleConstraintForSecretaireGeneral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Pour PostgreSQL, nous devons d'abord supprimer l'ancienne contrainte
        // puis créer une nouvelle avec les valeurs mises à jour
        
        // Étape 1: Supprimer l'ancienne contrainte
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        
        // Étape 2: Ajouter la nouvelle contrainte avec secretaire_general inclus
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('employe', 'responsable_hierarchique', 'drh', 'president', 'secretaire_general'))");
        
        // Alternative si vous connaissez le nom exact de votre contrainte :
        // DB::statement('ALTER TABLE users DROP CONSTRAINT users_role_check');
        // DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('employe', 'responsable_hierarchique', 'drh', 'president', 'secretaire_general'))");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remettre l'ancienne contrainte sans secretaire_general
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('agent', 'responsable_hierarchique', 'drh', 'president', 'admin')))");
    }
}