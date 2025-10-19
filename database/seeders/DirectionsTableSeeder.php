<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use App\Models\Direction;

class DirectionsTableSeeder extends Seeder
{
    public function run()
    {
      
        $directions = [
            ['nom' => 'Direction Générale', 'code' => 'DG', 'description' => 'Direction générale de l\'UTS'],
            ['nom' => 'Direction des Ressources Humaines', 'code' => 'DRH', 'description' => 'Direction des ressources humaines'],
            ['nom' => 'Direction Financière', 'code' => 'DF', 'description' => 'Direction financière et comptable'],
            ['nom' => 'Direction Technique', 'code' => 'DT', 'description' => 'Direction technique et opérationnelle'],
            ['nom' => 'Direction Commerciale', 'code' => 'DC', 'description' => 'Direction commerciale et marketing'],
            ['nom' => 'Direction des Systèmes d\'Information', 'code' => 'DSI', 'description' => 'Direction des systèmes d\'information'],
            ['nom' => 'Direction Logistique', 'code' => 'DL', 'description' => 'Direction logistique et supply chain'],
            ['nom' => 'Direction Qualité', 'code' => 'DQ', 'description' => 'Direction qualité et processus'],
        ];
        foreach ($directions as $direction) {
            Direction::create($direction);
        }
    }
}