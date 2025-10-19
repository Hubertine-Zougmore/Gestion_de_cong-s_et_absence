<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Vérifier si la table existe déjà
        if (Schema::hasTable('directions')) {
            // Ajouter les colonnes manquantes
            Schema::table('directions', function (Blueprint $table) {
                if (!Schema::hasColumn('directions', 'code')) {
                    $table->string('code', 50)->unique()->after('nom');
                }
                if (!Schema::hasColumn('directions', 'description')) {
                    $table->text('description')->nullable()->after('code');
                }
            });
        } else {
            // Créer la table si elle n'existe pas
            Schema::create('directions', function (Blueprint $table) {
                $table->id();
                $table->string('nom', 255);
                $table->string('code', 50)->unique();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // Ne pas supprimer la table en rollback pour éviter de perdre des données
        Schema::table('directions', function (Blueprint $table) {
            $table->dropColumn(['code', 'description']);
        });
    }
};