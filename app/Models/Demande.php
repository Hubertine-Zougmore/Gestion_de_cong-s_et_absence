<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class Demande extends Model
{
    protected $fillable = [
        'user_id', 
        'type', 
        'date_debut', 
        'date_fin', 
        'nombre_jours', 
        'motif', 
        'statut', 
        'annee_conge',
        'duree_urgence',
        'commentaire_drh', 
        'traite_par', 
        'traite_le', 
        'date_approbation',
        'commentaire_responsable', 
        'commentaire_sg', 
        'commentaire_president',
        'justificatif',
        'commentaire_secretaire_general',
        'etape_workflow', // Nouveau champ
        'demande_mere_id' ,
         'nom',
        'prenom', 
        'email',
        'password',
        'matricule',
        'direction',
        'poste',
        'telephone',
        'sexe',
        'role',
        'date_embauche',
        'is_active',
        'approuve_par_sg',
        'approuve_par_rh',
        'date_approbation_sg',
        'date_approbation_rh',
        'commentaire_sg',
        'commentaire_rh',
         'commentaire_traitement',
        'niveau_validation',
        'est_conge_annuel',
        'validation_secretaire',
        'validation_responsable',
        'date_validation_secretaire',
        'date_validation_responsable'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'date_debut', 
        'date_fin', 
        'traite_le',
        'created_at',
        'updated_at', 
        'date_approbation',
         'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'date_embauche' => 'date',
    ];


    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'traite_le' => 'datetime',
        'date_approbation' => 'datetime',
        'nombre_jours' => 'integer',
        'annee_conge' => 'integer',
        //////////////////////////////
        'approuve_par_sg' => 'boolean',
        'approuve_par_rh' => 'boolean',
        'date_approbation_sg' => 'datetime',
        'date_approbation_rh' => 'datetime',
        //////////////////////////////////
        'date_validation_secretaire' => 'datetime',
        'date_validation_responsable' => 'datetime',
        'est_conge_annuel' => 'boolean',
        'validation_secretaire' => 'boolean',
        'validation_responsable' => 'boolean',
    ];
    /**
     * Statuts possibles pour les congés annuels
     */
    const STATUTS = [
        'en_attente_sg' => 'En attente SG',
        'approuve_par_sg' => 'Approuvé par SG',
        'en_attente_rh' => 'En attente RH',
        'approuve_par_rh' => 'Approuvé définitivement',
        'rejete_par_sg' => 'Rejeté par SG',
        'rejete_par_rh' => 'Rejeté définitivement',
    ];

        /**
     * Scope pour les demandes en attente de validation responsable
     */
    public function scopeEnAttenteResponsable($query)
    {
        return $query->where('statut', 'en_attente_responsable');
    }

    /**
     * Scope pour les demandes en attente de validation SG
     */
    public function scopeEnAttenteSecretaire($query)
    {
        return $query->where('statut', 'en_attente_secretaire');
    }

    /**
     * Vérifie si c'est un congé annuel
     */
    public function getEstCongeAnnuelAttribute()
    {
        return $this->type === 'conge_annuel';
    }

    /**
     * Détermine le prochain niveau de validation
     */
    public function getProchaineValidationAttribute()
    {
        if ($this->est_conge_annuel) {
            if (!$this->validation_secretaire) {
                return 'secretaire';
            }
            if (!$this->validation_responsable) {
                return 'responsable';
            }
        }
        
        return $this->statut === 'en_attente' ? 'unique' : 'termine';
    }

    /**
     * Vérifie si le SG peut traiter cette demande
     */
    public function getPeutTraiterParSecretaireAttribute()
    {
        return $this->est_conge_annuel && 
               $this->statut === 'en_attente_secretaire' &&
               !$this->validation_secretaire;
    }

    /**
     * Vérifie si le responsable peut traiter cette demande
     */
    public function getPeutTraiterParResponsableAttribute()
    {
        if (!$this->est_conge_annuel) {
            return $this->statut === 'en_attente';
        }
        
        return $this->statut === 'en_attente_responsable' && 
               $this->validation_secretaire &&
               !$this->validation_responsable;
    }

    /**
     * Vérifie si l'utilisateur peut se connecter
     */
    public function canLogin()
    {
        return $this->is_active;
    }

    /**
     * Scope pour les utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les utilisateurs inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }


    // Relation avec l'utilisateur
   public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}


    public function traite_par_user()
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function approuvePar()
    {
        return $this->belongsTo(User::class, 'approuve_par');
    }

    // Scopes
    public function scopeSansUtilisateur($query, $userId)
    {
        return $query->where('user_id', '!=', $userId);
    }

    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?: auth()->id();
        return $query->where('user_id', $userId);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeApprouvees($query)
    {
        return $query->where('statut', 'approuve');
    }

    public function scopeRejetees($query)
    {
        return $query->where('statut', 'rejete');
    }

    public function scopeUrgentes($query)
    {
        return $query->where('statut', 'en_attente')
                    ->whereRaw('(julianday("now") - julianday(created_at)) * 24 >= duree_urgence');
    }

    public function scopePourAnnee($query, $annee = null)
    {
        $annee = $annee ?: date('Y');
        return $query->where('annee_conge', $annee);
    }

    public function scopeParType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accesseurs
    public function getDateDebutFormateeAttribute()
    {
        return $this->date_debut ? 
            Carbon::parse($this->date_debut)->format('d/m/Y') : '';
    }

    public function getDateFinFormateeAttribute()
    {
        return $this->date_fin ? 
            Carbon::parse($this->date_fin)->format('d/m/Y') : '';
    }

    public function getJustificatifUrlAttribute()
    {
        if (!$this->justificatif) {
            return null;
        }
        
        return URL::temporarySignedRoute(
            'demandes.downloadJustificatif', 
            now()->addMinutes(30), 
            ['id' => $this->id]
        );
    }

    public function getStatutBadgeClassAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'bg-warning',
            'approuve' => 'bg-success',
            'rejete' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    public function getDureeEnJoursAttribute()
    {
        if (!$this->date_debut || !$this->date_fin) {
            return 0;
        }
        return $this->calculerJoursOuvrables($this->date_debut, $this->date_fin);
    }

    // En heures
public function getDelaiRestantHeuresAttribute()
{
    if ($this->statut !== 'en_attente' || !$this->date_debut) {
        return null;
    }

    // Différence entre aujourd'hui et la date de début
    $delaiRestant = now()->diffInHours(Carbon::parse($this->date_debut), false);

    return $delaiRestant;
}

// En jours
public function getDelaiRestantJoursAttribute()
{
    if ($this->statut !== 'en_attente' || !$this->date_debut) {
        return null;
    }

    $delaiRestant = now()->diffInDays(Carbon::parse($this->date_debut), false);

    return $delaiRestant;
}


    public function getEstEnRetardAttribute()
    {
        if ($this->statut !== 'en_attente') {
            return false;
        }
        
        return $this->delai_restant_heures <= 0;
    }

    public function getEstUrgentAttribute()
    {
        if ($this->statut !== 'en_attente') {
            return false;
        }
        
        return $this->delai_restant_heures > 0 && $this->delai_restant_heures <= 24;
    }


    public function getEstDeMaDirectionAttribute()
    {
        if (!Auth::check()) return false;
        return $this->user->direction_id === Auth::user()->direction_id;
    }

    public function getPeutTraiterParDRHAttribute()
    {
        if (!Auth::check()) return false;
        if ($this->user_id === Auth::id()) return false;
        
        return $this->est_urgent || !$this->est_de_ma_direction;
    }

    // Méthodes utilitaires
    public function calculerJoursOuvrables($dateDebut, $dateFin)
    {
        $debut = Carbon::parse($dateDebut);
        $fin = Carbon::parse($dateFin);
        
        $jours = 0;
        $current = $debut->copy();

        while ($current->lte($fin)) {
            if (!in_array($current->dayOfWeek, [0, 6])) {
                $jours++;
            }
            $current->addDay();
        }

        return $jours;
    }

    public function peutEtreModifiee()
    {
        return $this->statut === 'en_attente';
    }

    public function peutEtreSupprimee()
    {
        return $this->statut === 'en_attente';
    }
    // Ajouter ces accesseurs
public function getDureeHeuresAttribute()
{
    return $this->nombre_jours * 24;
}

public function getEstExactement72hAttribute()
{
    return $this->duree_heures == 72;
}

public function getEstMoins72hAttribute()
{
    return $this->duree_heures < 72;
}

public function getEstPlus72hAttribute()
{
    return $this->duree_heures > 72;
}

public function getPeutTraiterAttribute()
{
    if ($this->statut !== 'en_attente'){
        return false; 
    }else {return true;}
    
}

public function getPresidentDoitTraiterAttribute()
{
    return $this->statut === 'en_attente' && $this->est_plus_72h;
}
/////////////////////////////////Jouinssance///////////////////////////
public function demandeJouissance()
{
    return $this->hasOne(DemandeJouissance::class, 'demande_conge_id');
}

public function creerDemandeJouissance()
{
    if ($this->type === 'conge_annuel' && $this->statut === 'approuve') {
        // Trouver le responsable hiérarchique de la direction de l'employé
        $responsable = User::where('role', 'responsable_hierarchique')
            ->where('direction_id', $this->user->direction_id)
            ->first();

        // CAS SPÉCIAL : Si pas de responsable hiérarchique trouvé 
        // (ex: direction DRH sans responsable spécifique)
        // Alors le DRH devient le responsable pour la jouissance
        if (!$responsable) {
            $responsable = User::where('role', 'drh')->first();
        }
        elseif (!$responsable) {
            $responsable = User::where('role', 'president')->first();
        }

        if (!$responsable) {
            \Log::warning('Aucun responsable trouvé pour valider la jouissance', [
                'demande_id' => $this->id,
                'direction_id' => $this->user->direction_id
            ]);
            return null;
        }

        return DemandeJouissance::create([
            'demande_conge_id' => $this->id,
            'user_id' => $this->user_id,
            'responsable_id' => $responsable->id,
            'date_debut_jouissance' => $this->date_debut,
            'date_fin_jouissance' => $this->date_fin,
            'nombre_jours_jouissance' => $this->nombre_jours,
        ]);
    }
    return null;
}

public function getStatutCompletAttribute()
{
    if ($this->statut === 'rejete') {
        return 'Rejeté';
    }
    
    if ($this->statut === 'en_attente') {
        return 'En attente d\'approbation';
    }
    
    if ($this->statut === 'approuve') {
        // Pour les congés annuels, vérifier la jouissance
        if ($this->type === 'conge_annuel') {
            $jouissance = $this->demandeJouissance;
            
            if (!$jouissance) {
                return 'Approuvé - Demande de jouissance en cours';
            }
            
            switch ($jouissance->statut) {
                case 'en_attente':
                    return 'Approuvé - En attente d\'autorisation de jouissance';
                case 'approuve':
                    return 'Autorisé - Congé effectif';
                case 'rejete':
                    return 'Jouissance refusée';
            }
        }
        
        return 'Approuvé';
    }
    
    return 'Statut inconnu';
}
}