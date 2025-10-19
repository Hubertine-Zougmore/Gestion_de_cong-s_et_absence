<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_types_conges_table.php
public function up()
{
    Schema::create('types_conges', function (Blueprint $table) {
        $table->id();
        $table->string('nom'); // ex: "conge_annuel", "autorisation_absence"
        $table->integer('duree_max'); // ex: 30 jours
        $table->string('conditions')->nullable(); 
        // ex: "anciennete>=11", "sexe=feminin"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('types_conges');
    }
};
