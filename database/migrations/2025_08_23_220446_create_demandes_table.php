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
    Schema::create('demandes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('type'); // congé, absence, etc.
        $table->date('date_debut');
        $table->date('date_fin');
        $table->integer('nombre_jours');
        $table->string('statut')->default('en_attente');
        $table->text('raison')->nullable();
        $table->text('motif')->nullable(); 
        $table->timestamps();
    $table->string('statut')->default('en_attente_sg'); // Nouveaux statuts
    $table->boolean('approuve_par_sg')->default(false);
    $table->boolean('approuve_par_rh')->default(false);
    $table->timestamp('date_approbation_sg')->nullable();
    $table->timestamp('date_approbation_rh')->nullable();
    $table->text('commentaire_sg')->nullable();
    $table->text('commentaire_rh')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandes');
    }
};
