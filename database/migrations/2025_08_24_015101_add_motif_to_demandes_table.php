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
    Schema::table('demandes', function (Blueprint $table) {
        if (!Schema::hasColumn('demandes', 'motif')) {
            $table->text('motif')->nullable()->after('date_fin');
        }
    });
}

public function down()
{
    Schema::table('demandes', function (Blueprint $table) {
        $table->dropColumn('motif');
    });
}
};
