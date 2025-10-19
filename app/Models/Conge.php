<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Conge extends Model
{
    use HasFactory;
     protected $table = 'demandes';

    protected $fillable = [
        'user_id',
        'type',
        'date_debut',
        'date_fin',
        'nb_jours',
        'motif',
        'statut',
        'approuve_par',
        'commentaire_approbation',
        'date_approbation'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin' => 'date',
        'date_approbation' => 'datetime'
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approbateur()
    {
        return $this->belongsTo(User::class, 'approuve_par');
    }

    // Méthodes utiles
    public function isApprouve()
    {
        return $this->statut === 'approuve';
    }

    public function isRefuse()
    {
        return $this->statut === 'refuse';
    }

    public function isEnAttente()
    {
        return $this->statut === 'en_attente';
    }

    public function calculerNbJours()
    {
        $debut = Carbon::parse($this->date_debut);
        $fin = Carbon::parse($this->date_fin);
        
        // Calcul en jours ouvrables (excluant weekends)
        $jours = 0;
        while ($debut <= $fin) {
            if ($debut->isWeekday()) {
                $jours++;
            }
            $debut->addDay();
        }
        
        return $jours;
    }

    public function getStatutColorAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'warning',
            'approuve' => 'success',
            'refuse' => 'danger',
            default => 'secondary'
        };
    }

    public function getTypeLibelleAttribute()
    {
        return match($this->type) {
            'conge_annuel' => 'Congé annuel',
            'conge_maladie' => 'Congé maladie',
            'conge_maternite' => 'Congé maternité',
            'permission' => 'Permission',
            'autre' => 'Autre',
            default => $this->type
        };
    }
    
}