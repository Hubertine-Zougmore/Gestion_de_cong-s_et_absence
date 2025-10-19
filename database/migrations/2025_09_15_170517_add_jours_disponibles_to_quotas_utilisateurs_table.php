<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('quotas_utilisateurs', function (Blueprint $table) {
            if (!Schema::hasColumn('quotas_utilisateurs', 'jours_disponibles')) {
                $table->integer('jours_disponibles')->default(0)->after('jours_alloues');
            }
        });
    }

    public function down()
    {
        Schema::table('quotas_utilisateurs', function (Blueprint $table) {
            $table->dropColumn('jours_disponibles');
        });
    }
};