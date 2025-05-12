<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * Les attributs qui doivent être ajoutés aux tableaux.
     *
     * @var array<string>
     */
    protected $appends = ['name', 'profile_image_url'];

    /**
     * Les attributs pouvant être remplis en masse.
     *
     * @var array<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'startup_name',
        'code_fiscal',
        'startup_email',
        'startup_phone',
        'speciality',
        'description',
        'role',
        'status',
        'profile_image',
        'cv_file',
        'startup_id' // Ajouté depuis votre version
    ];

    /**
     * Les attributs cachés pour la sérialisation.
     *
     * @var array<string> 
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Vérifie si l'utilisateur a confirmé son email.
     * Les formateurs sont exemptés de cette vérification.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        // Si l'utilisateur est un formateur, on considère l'email comme vérifié
        if ($this->role === 'formateur') {
            return true;
        }

        // Pour les autres rôles, utiliser le comportement normal
        return !is_null($this->email_verified_at);
    }
    
    /**
     * Obtient le nom complet de l'utilisateur.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    /**
     * Vérifie si l'utilisateur est un administrateur.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    /**
     * Relation avec les formations achetées par l'utilisateur.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function formations()
    {
        return $this->belongsToMany(Formation::class)
                    ->withPivot('date_achat', 'date_expiration')
                    ->withTimestamps();
    }
    
    /**
     * Get the startup that owns the worker.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function startup()
    {
        return $this->belongsTo(User::class, 'startup_id');
    }

    /**
     * Get the workers that belong to the startup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workers()
    {
        return $this->hasMany(User::class, 'startup_id')
                    ->where('role', 'worker');
    }
    
    /**
     * Get the groups owned by the startup.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedGroups()
    {
        return $this->hasMany(Group::class, 'startup_id');
    }

    /**
     * Get the groups that the worker belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_worker', 'worker_id', 'group_id')
                    ->withTimestamps();
    }
    
    /**
     * Obtient l'URL de l'image de profil de l'utilisateur.
     * Gère à la fois le stockage local et AWS S3 avec URLs présignées.
     *
     * @return string|null
     */
    public function getProfileImageUrlAttribute()
    {
        if (!$this->profile_image) {
            return null;
        }
        
        // Si c'est déjà une URL complète, la retourner telle quelle
        if (filter_var($this->profile_image, FILTER_VALIDATE_URL)) {
            return $this->profile_image;
        }
        
        // Vérifier si c'est un chemin S3 (commence par 'profile_images/')
        if (str_starts_with($this->profile_image, 'profile_images/')) {
            // Génère une URL S3 présignée valide pour 30 minutes
            try {
                return Storage::disk('s3')->temporaryUrl($this->profile_image, now()->addMinutes(30));
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la génération de l\'URL S3: ' . $e->getMessage(), [
                    'user_id' => $this->id,
                    'path' => $this->profile_image
                ]);
                // En cas d'erreur, on continue pour essayer le fallback local
            }
        }
        
        // Fallback pour les anciennes images stockées localement
        return '/storage/' . str_replace('public/', '', $this->profile_image);
    }
}