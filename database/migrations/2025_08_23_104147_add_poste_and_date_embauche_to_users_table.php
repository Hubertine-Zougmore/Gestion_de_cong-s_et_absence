<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter poste après departement
            if (!Schema::hasColumn('users', 'poste')) {
                $table->string('poste')->nullable()->after('departement');
            }
            
            // Ajouter date_embauche après telephone
            if (!Schema::hasColumn('users', 'date_embauche')) {
                $table->date('date_embauche')->nullable()->after('telephone');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['poste', 'date_embauche']);
        });
    }
};