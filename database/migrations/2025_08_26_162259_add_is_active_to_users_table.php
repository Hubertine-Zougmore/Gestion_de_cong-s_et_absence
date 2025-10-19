<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('password');
            }
            
            // Ajoutez également les autres colonnes manquantes si nécessaire
            if (!Schema::hasColumn('users', 'matricule')) {
                $table->string('matricule')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'direction')) {
                $table->string('direction')->nullable()->after('matricule');
            }
            if (!Schema::hasColumn('users', 'poste')) {
                $table->string('poste')->nullable()->after('direction');
            }
            if (!Schema::hasColumn('users', 'telephone')) {
                $table->string('telephone')->nullable()->after('poste');
            }
            if (!Schema::hasColumn('users', 'date_embauche')) {
                $table->date('date_embauche')->nullable()->after('telephone');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'matricule', 'direction', 'poste', 'telephone', 'date_embauche']);
        });
    }
};