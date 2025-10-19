<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParametreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/ParametreSeeder.php
public function run()
{
    $params = [
        [
            'code' => 'mois_conges_autorises',
            'valeur' => json_encode(['juillet', 'semptembre']),
            'type' => 'array',
            'description' => 'Mois autorisés pour les congés (noms anglais)'
        ],
        [
            'code' => 'jours_conges_annuels',
            'valeur' => '25',
            'type' => 'integer',
            'description' => 'Nombre de jours de congés annuels'
        ],
        [
            'code' => 'notifications_actives',
            'valeur' => 'true',
            'type' => 'boolean',
            'description' => 'Activer les notifications'
        ]
    ];

    foreach ($params as $param) {
        Parametre::create($param);
    }
}
}
