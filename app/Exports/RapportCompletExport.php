<?php

namespace App\Exports;

use App\Models\Rapport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RapportCompletExport implements WithMultipleSheets
{
    protected $rapport;

    public function __construct(Rapport $rapport)
    {
        $this->rapport = $rapport;
    }

    public function sheets(): array
    {
        return [
            new ResumeExport($this->rapport),
            new DetailsExport($this->rapport),
            new StatistiquesExport($this->rapport),
        ];
    }
}