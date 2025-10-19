<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{

    use Notifiable;
    
    // ...

    use HasRoles;
    use HasApiTokens, HasFactory, Notifiable;
    public static function getRolesList()
    {
        return [
            'agent' => 'Agent',
            'responsable_hierarchique' => 'Responsable Hiérarchique',
            'drh' => 'DRH',
            'admin' => 'Administrateur',
            'secretaire_general' => 'Secrétaire Général',
            'president' => 'Président',
        ];
    }

   const ROLE_AGENT = 'agent';
    const ROLE_RESPONSABLE = 'responsable_hierarchique';
    const ROLE_DRH = 'drh';
    const ROLE_ADMIN = 'admin';
    const ROLE_SECRETAIRE_GENERAL = 'secretaire_general';
    const ROLE_PRESIDENT = 'president';

    const ROLES = [
        self::ROLE_AGENT => 'Agent',
        self::ROLE_RESPONSABLE => 'Responsable hiérarchique',
        self::ROLE_DRH => 'DRH',
        self::ROLE_ADMIN => 'Admin',
        self::ROLE_SECRETAIRE_GENERAL  => 'Secrétaire Général',
        self::ROLE_PRESIDENT => 'Président',
    ];

 public function users()
    {
        return $this->hasMany(User::class);
    }
    
    // ... reste du modèle

    public static function getRoles()
    {
        return self::ROLES;
    }
    public static function getRoleKeys()
    {
        return array_keys(self::ROLES);
    }


    protected $fillable = [
       // 'ldap_username','duree_urgence',
       'matricule',
        'nom',
        'prenom',
        'email',
        'email_verified_at',
        'password',
        'departement',
        'telephone',
         'role',
        'poste',
        'photo',
        'date_embauche',
        'is_active',
        'direction',
        'motif', 
    'statut', 
    'user_id',
    'justificatif',
    'type_justificatif',
    'conge_annuel_restant', 'absence_restante', 'maternite_restante',
    'conge_annuel_total', 'absence_total', 'maternite_total'
    ];
    // pour l'URL de la photo
public function getPhotoUrlAttribute()
{
    if ($this->photo) {
        return asset('storage/photos/' . $this->photo);
    }
    
    return asset('images/default-avatar.png');
}
    public static function rules()
{
    return [
        'nom' => 'required|string|max:50',
        'prenom' => 'required|string|max:50',
        // ... autres règles
    ];
}
    // Ajoutez ceci pour générer automatiquement un matricule si vide
protected static function boot()
{
    parent::boot();

    static::creating(function ($user) {
        if (empty($user->matricule)) {
            $user->matricule = 'EMP' . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        }
    });
}


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
         'date_embauche' => 'date',
        'statut_actif' => 'boolean',
         'date_debut' => 'date',
        'date_fin' => 'date',
        'is_active' => 'boolean',
       
    ];
    // Scope pour les utilisateurs actifs
public function scopeActive($query)
{
    return $query->where('is_active', true);
}

// Scope pour les utilisateurs inactifs
public function scopeInactive($query)
{
    return $query->where('is_active', false);
}
    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Vérifier si la demande est urgente (< 72h)
    public function getEstUrgentAttribute()
    {
        $delaiCreation = $this->created_at->diffInHours(now());
        return $delaiCreation <= $this->duree_urgence;
    }

    // Vérifier si la demande peut encore être traitée
    public function getPeutEtreTraiteeAttribute()
    {
        return $this->est_urgent && $this->statut === 'en_attente';
    }

    // Scope pour les demandes traitables par le DRH
    public function scopeTraitableParDrh($query)
    {
        return $query->where('statut', 'en_attente')
                    ->whereHas('user', function($q) {
                        $q->where('departement', '!=', 'DRH'); // Exclure les DRH eux-mêmes
                    });
    }

        // Créer un accesseur pour 'name' qui combine nom + prenom
    public function getNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }
// Si vous voulez aussi pouvoir définir 'name' 
    public function setNameAttribute($value)
    {
        $parts = explode(' ', $value, 2);
        $this->nom = $parts[0] ?? '';
        $this->prenom = $parts[1] ?? '';
    }

 /**
     * Vérifie si l'utilisateur appartient à une direction spécifique
     */
    public function appartientADirection($directionId)
    {
        return $this->direction_id == $directionId;
    }

    // Relations
   public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function agent()
    {
        return $this->hasMany(User::class, 'agent_id');
    }

    // Vérifier si l'utilisateur est admin
public function isAdmin(): bool
{
    return $this->role === 'admin'; // L'admin n'a pas de supérieur
        // Ou vous pouvez ajouter une colonne is_admin boolean
    }

    // Vos autres relations (congés, etc.)

    public function conges()
    {
        return $this->hasMany(Conge::class);
    }

    public function congesApprouves()
    {
        return $this->hasMany(Conge::class, 'approuve_par');
    }
    public function direction()
    {
       
    return $this->belongsTo(Direction::class);
    }
    
    // Autres relations...
    public function demandes()
    {
        return $this->hasMany(Demande::class);
    }


// Méthodes pour vérifier les quotas
public function canTakeCongeAnnuel($jours)
{
    return $this->conge_annuel_restant >= $jours && $this->isCongeAnnuelPeriod();
}

public function canTakeAbsence($jours)
{
    return $this->absence_restante >= $jours;
}

public function canTakeMaternite($jours)
{
    return $this->maternite_restante >= $jours;
}

// Vérification de la période pour congés annuels (août et septembre)
public function isCongeAnnuelPeriod()
{
    $month = now()->month;
    return $month == 8 || $month == 9; // Août = 8, Septembre = 9
}


public function poste()
{
    return $this->belongsTo(Poste::class);
}

// Méthode pour déduire les jours
public function deductLeaveDays($type, $jours)
{
    switch ($type) {
        case 'conge_annuel':
            $this->conge_annuel_restant -= $jours;
            break;
        case 'absence':
            $this->absence_restante -= $jours;
            break;
        case 'maternite':
            $this->maternite_restante -= $jours;
            break;
    }
    $this->save();
}
////////////////////////////SG///////////////////////
public function isSecretaireGeneral()
{
    return $this->role === 'secretaire_general';
}

/**
 * Vérifier si l'utilisateur peut traiter toutes les demandes
 */
public function canTreatAllRequests()
{
    return in_array($this->role, ['secretaire_general', 'president']);
}

/**
 * Obtenir le niveau hiérarchique (pour la logique de traitement)
 */
public function getHierarchyLevel()
{
    return match($this->role) {
        'employe' => 1,
        'responsable_hierarchique' => 2,
        'drh' => 3,
        'secretaire_general' => 4,
        'president' => 5,
        default => 0
    };
}
    
}
