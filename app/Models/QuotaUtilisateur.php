<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotaUtilisateur extends Model
{
    use HasFactory;

    protected $table = 'quotas_utilisateurs';

    protected $fillable = [
        'user_id',
        'type_conge',
        'annee',
        'jours_utilises',
        'jours_disponibles',
        'jours_restants',
    ];

    protected $casts = [
        'jours_utilises' => 'integer',
        'jours_disponibles' => 'integer',
        'annee' => 'integer',
    ];
    

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Méthodes utilitaires
    public function joursRestants()
    {
        return $this->jours_disponibles - $this->jours_utilises;
    }

    public function peutPrendreCongé($nombreJours)
    {
        return $this->joursRestants() >= $nombreJours;
    }

    public function pourcentageUtilise()
    {
        if ($this->jours_disponibles == 0) {
            return 0;
        }
        
        return round(($this->jours_utilises / $this->jours_disponibles) * 100, 1);
    }
     /**
     * Scope pour obtenir le quota d'une année spécifique
     */
    public function scopeAnnee($query, $annee)
    {
        return $query->where('annee', $annee);
    }
     /**
     * Scope pour obtenir le quota d'un type spécifique
     */
    public function scopeTypeConge($query, $typeConge)
    {
        return $query->where('type_conge', $typeConge);
    }
}