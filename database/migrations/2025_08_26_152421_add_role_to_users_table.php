<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('role', 50)->default('agent')->after('email');
    });

    // AJOUTEZ 'admin' dans la liste des valeurs autorisées
    DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check 
                   CHECK (role IN ('agent', 'responsable_hierarchique', 'drh', 'president', 'sg', 'admin'))");
}

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
        
        // Supprimez aussi la contrainte
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
    }
};