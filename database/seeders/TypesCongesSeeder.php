<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesCongesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
    {
        DB::table('types_conges')->insert([
    ['nom' => 'conge_annuel', 'duree_max' => 30, 'conditions' => 'anciennete>=11'],
    ['nom' => 'autorisation_absence', 'duree_max' => 10, 'conditions' => null],
    ['nom' => 'conge_maternite', 'duree_max' => 98, 'conditions' => 'sexe=feminin'],
]);

    }
}
