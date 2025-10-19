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
            if (!Schema::hasColumn('users', 'departement')) {
                $table->string('departement')->nullable()->after('matricule');
            }
            
            if (!Schema::hasColumn('users', 'poste')) {
                $table->string('poste')->nullable()->after('departement');
            }
            
            if (!Schema::hasColumn('users', 'date_embauche')) {
                $table->date('date_embauche')->nullable()->after('poste');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['departement', 'poste', 'date_embauche']);
        });
    }
};
