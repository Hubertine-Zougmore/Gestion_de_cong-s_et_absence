<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nom');          // Colonne ajoutée
            $table->string('prenom');       // Colonne ajoutée
            $table->string('email')->unique();
            $table->string('password');
            $table->string('matricule')->unique();
            $table->string('departement')->nullable();
            $table->string('telephone')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
