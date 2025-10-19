<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemandesJouissanceTable extends Migration
{
    public function up()
    {
        Schema::create('demandes_jouissance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demande_conge_id')->constrained('demandes')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('responsable_id')->constrained('users')->onDelete('cascade');
            
            $table->date('date_debut_jouissance');
            $table->date('date_fin_jouissance');
            $table->integer('nombre_jours_jouissance');
            
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('commentaire_responsable')->nullable();
            
            $table->timestamp('traite_le')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('demandes_jouissance');
    }
}