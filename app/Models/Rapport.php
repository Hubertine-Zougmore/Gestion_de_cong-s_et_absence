<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
         'titre',
    'type', 
    'description',
    'date_debut', // AJOUTEZ
    'date_fin',   // AJOUTEZ
    'donnees',
    'user_id'
    ];

    protected $casts = [
         'donnees' => 'array',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'valide_le' => 'datetime',
        'donnees_statistiques' => 'array',
        'destinataires' => 'array',
        'confidentiel' => 'boolean'
    ];

    // Relations
    public function redigeePar()
    {
        return $this->belongsTo(User::class, 'redige_par');
    }

    public function valideePar()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    // Accesseurs
    public function getPeriodeFormateeAttribute()
    {
        return $this->periode_debut->format('d/m/Y') . ' - ' . $this->periode_fin->format('d/m/Y');
    }

    public function getStatutBadgeAttribute()
    {
        $badges = [
            'brouillon' => 'bg-gray-100 text-gray-800',
            'en_revision' => 'bg-yellow-100 text-yellow-800',
            'finalise' => 'bg-green-100 text-green-800',
            'envoye' => 'bg-blue-100 text-blue-800'
        ];

        return $badges[$this->statut] ?? 'bg-gray-100 text-gray-800';
    }

    // Méthodes utilitaires
    public function peutEtreModifie()
    {
        return in_array($this->statut, ['brouillon', 'en_revision']);
    }
      public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePourPeriode($query, $debut, $fin)
    {
        return $query->where('date_debut', '>=', $debut)
                    ->where('date_fin', '<=', $fin);
    }

    public function genererStatistiques()
    {
        $debut = $this->periode_debut;
        $fin = $this->periode_fin;

        return [
            'total_demandes' => Demande::whereBetween('created_at', [$debut, $fin])->count(),
            'demandes_approuvees' => Demande::whereBetween('created_at', [$debut, $fin])->where('statut', 'approuve')->count(),
            'demandes_rejetees' => Demande::whereBetween('created_at', [$debut, $fin])->where('statut', 'rejete')->count(),
            'demandes_en_attente' => Demande::whereBetween('created_at', [$debut, $fin])->where('statut', 'en_attente')->count(),
            'demandes_par_type' => Demande::whereBetween('created_at', [$debut, $fin])
                ->selectRaw('type, COUNT(*) as total')
                ->groupBy('type')
                ->pluck('total', 'type')
                ->toArray(),
            'moyenne_jours' => Demande::whereBetween('created_at', [$debut, $fin])
                ->get()
                ->avg(function($demande) {
                    return $demande->date_debut->diffInDays($demande->date_fin) + 1;
                }),
            'employes_actifs' => Demande::whereBetween('created_at', [$debut, $fin])
                ->distinct('user_id')
                ->count('user_id')
        ];
    }
}
