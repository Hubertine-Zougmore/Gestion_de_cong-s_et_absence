<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DemandeJouissance extends Model
{
    protected $table = 'demandes_jouissance';

    protected $fillable = [
        'demande_conge_id', 'user_id', 'responsable_id',
        'date_debut_jouissance', 'date_fin_jouissance', 'nombre_jours_jouissance',
        'statut', 'commentaire_responsable', 'traite_le'
    ];

    protected $dates = ['date_debut_jouissance', 'date_fin_jouissance', 'traite_le'];

    public function demandeConge()
    {
        return $this->belongsTo(Demande::class, 'demande_conge_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}