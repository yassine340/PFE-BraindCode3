<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormationUser extends Model
{
    protected $table = 'formation_user';
    
    protected $fillable = [
        'user_id',
        'formation_id',
        'date_achat',
        'date_expiration',
    ];

    protected $casts = [
        'date_achat' => 'datetime',
        'date_expiration' => 'datetime',
    ];

    /**
     * Relation avec le modèle User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le modèle Formation
     */
    public function formation(): BelongsTo
    {
        return $this->belongsTo(Formation::class);
    }
    
    /**
     * Retourne le temps restant avant expiration sous forme de chaîne
     */
    public function getTempsRestant()
    {
        $now = Carbon::now();
        
        if ($now->greaterThan($this->date_expiration)) {
            return 'Expiré';
        }
        
        $diff = $now->diffForHumans($this->date_expiration, [
            'parts' => 2,
            'join' => true,
            'short' => false,
        ]);
        
        return $diff;
    }
    
    /**
     * Retourne les données pour un compteur en jours/heures/minutes/secondes
     */
    public function getCompteurTemps()
    {
        $now = Carbon::now();
        
        if ($now->greaterThan($this->date_expiration)) {
            return [
                'expire' => true,
                'jours' => 0,
                'heures' => 0,
                'minutes' => 0,
                'secondes' => 0
            ];
        }
        
        $diff = $now->diff($this->date_expiration);
        
        // Assurez-vous que toutes les valeurs sont converties en entiers
        return [
            'expire' => false,
            'jours' => (int) $diff->days, // Important: convertir en entier
            'heures' => (int) $diff->h,
            'minutes' => (int) $diff->i,
            'secondes' => (int) $diff->s
        ];
    }
}