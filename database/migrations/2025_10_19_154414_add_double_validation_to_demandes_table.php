<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDoubleValidationToDemandesTable extends Migration
{
    public function up()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->boolean('validation_secretaire')->default(false);
            $table->boolean('validation_responsable')->default(false);
            $table->timestamp('date_validation_secretaire')->nullable();
            $table->timestamp('date_validation_responsable')->nullable();
            $table->string('statut')->default('en_attente')->change();
            
            // Index pour les performances
            $table->index(['statut', 'validation_secretaire']);
            $table->index(['statut', 'validation_responsable']);
        });
    }

    public function down()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'validation_secretaire',
                'validation_responsable', 
                'date_validation_secretaire',
                'date_validation_responsable'
            ]);
            $table->dropIndex(['statut', 'validation_secretaire']);
            $table->dropIndex(['statut', 'validation_responsable']);
        });
    }
}