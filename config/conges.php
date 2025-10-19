<?php

return [
    'types' => [
        'conge_annuel' => [
            'nom' => 'Congé annuel',
            'quota_jours' => 30,
            'description' => 'Congé annuel payé',
            'renouvelable' => true,
            'periode_renouvellement' => 'annuel', // annuel, mensuel, etc.
        ],
        'autorisation_absence' => [
            'nom' => 'Autorisation d\'absence',
            'quota_jours' => 10,
            'description' => 'Autorisation d\'absence exceptionnelle',
            'renouvelable' => true,
            'periode_renouvellement' => 'annuel',
        ],
        'conge_maternite' => [
            'nom' => 'Congé de maternité',
            'quota_jours' => 98, // 14 semaines
            'description' => 'Congé de maternité (14 semaines)',
            'renouvelable' => false,
            'periode_renouvellement' => null,
        ],
        
        'conge_maladie' => [
            'nom' => 'Congé maladie',
            'quota_jours' => null, // Illimité avec justificatif médical
            'description' => 'Congé pour maladie avec justificatif médical',
            'renouvelable' => true,
            'periode_renouvellement' => null,
        ],
        
    ],

    // Configuration générale
    'annee_civile' => true, // true = du 1er janvier au 31 décembre, false = autre période
    'report_conges' => true, // Possibilité de reporter les congés non pris
    'max_conges_consecutifs' => 30, // Maximum de jours de congés consécutifs
];