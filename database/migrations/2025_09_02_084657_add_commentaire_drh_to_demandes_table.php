<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->text('commentaire_drh')->nullable()->after('motif');
            $table->text('commentaire_responsable')->nullable()->after('commentaire_drh');
            $table->foreignId('traitee_par')->nullable()->after('commentaire_responsable')->constrained('users');
            $table->timestamp('traitee_le')->nullable()->after('traitee_par');
        });
    }

    public function down()
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropColumn([
                'commentaire_drh',
                'commentaire_responsable', 
                'traitee_par',
                'traitee_le'
            ]);
        });
    }
};