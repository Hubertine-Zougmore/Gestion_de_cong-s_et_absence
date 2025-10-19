<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->integer('conge_annuel_restant')->default(30);
        $table->integer('absence_restante')->default(10);
        $table->integer('maternite_restante')->default(98);
        $table->integer('conge_annuel_total')->default(30);
        $table->integer('absence_total')->default(10);
        $table->integer('maternite_total')->default(98);
    });
     // Créer la table quotas_utilisateurs si elle n'existe pas
        if (!Schema::hasTable('quotas_utilisateurs')) {
            Schema::create('quotas_utilisateurs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('type_conge');
                $table->integer('annee');
                $table->integer('jours_alloues')->default(0);
                $table->integer('jours_utilises')->default(0);
                $table->integer('jours_restants')->default(0);
                $table->timestamps();
                
                // Contrainte d'unicité
                $table->unique(['user_id', 'type_conge', 'annee']);
            });
        }
    }



    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'conge_annuel_restant',
                'absence_restante', 
                'maternite_restante',
                'conge_annuel_total',
                'absence_total',
                'maternite_total'
            ]);
        });
    }
};