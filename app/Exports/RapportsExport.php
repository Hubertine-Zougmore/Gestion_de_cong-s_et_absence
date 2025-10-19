<?php

namespace App\Exports;

use App\Models\Rapport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RapportsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function collection()
    {
        return collect([$this->rapport]);
    }

    public function headings(): array
    {
        return [
            'RAPPORT DE GESTION DES CONGÉS - UTS',
            '',
            'Période: ' . $this->rapport->date_debut->format('d/m/Y') . 
            ' au ' . $this->rapport->date_fin->format('d/m/Y')
        ];
    }

    public function map($rapport): array
    {
        $data = [
            // En-tête
            ['Titre du rapport', $rapport->titre],
            ['Période', $rapport->date_debut->format('d/m/Y') . ' - ' . $rapport->date_fin->format('d/m/Y')],
            ['Type', ucfirst($rapport->type)],
            ['Généré le', $rapport->created_at->format('d/m/Y à H:i')],
            ['Par', $rapport->user->name],
            [],
            
            // Statistiques générales
            ['STATISTIQUES GÉNÉRALES'],
            ['Total demandes', $rapport->donnees['statistiques']['total_demandes'] ?? 0],
            ['Demandes approuvées', $rapport->donnees['statistiques']['approuvees'] ?? 0],
            ['Demandes rejetées', $rapport->donnees['statistiques']['rejetees'] ?? 0],
            ['En attente', $rapport->donnees['statistiques']['en_attente'] ?? 0],
            [],
        ];

        // Ajouter les données par type
        if (isset($rapport->donnees['par_type'])) {
            $data[] = ['RÉPARTITION PAR TYPE DE DEMANDE'];
            foreach ($rapport->donnees['par_type'] as $type => $stats) {
                $data[] = [
                    $type,
                    $stats['total'] ?? 0,
                    $stats['approuvees'] ?? 0,
                    ($stats['taux_approbation'] ?? 0) . '%'
                ];
            }
        }

        return $data;
    }

    public function title(): string
    {
        return 'Rapport Congés';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style du titre principal
            1 => [
                'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4f46e5']]
            ],
            
            // Style des sections
            7 => [
                'font' => ['bold' => true, 'size' => 14],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'E6E6FA']]
            ],
            
            // Style des en-têtes de données
            8 => [
                'font' => ['bold' => true],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => 'F0F0F0']]
            ],
        ];
    }
}