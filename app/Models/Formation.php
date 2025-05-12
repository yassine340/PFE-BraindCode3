<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'prix',
        'estcertifiante',
        'image_formation',
        'category_id',
        'description',
        'duree',
        'est_valide', // Ajout du nouveau champ
        'est_publiee', // Ajout du champ de publication
        'user_id'  // Ajout du champ user_id pour le formateur

    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function lecons()
    {
        return $this->hasMany(Lecon::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
      // Relation avec l'utilisateur (formateur)
      public function user()
      {
          return $this->belongsTo(User::class);
      }
      public function users()
{
    return $this->belongsToMany(User::class)
                ->withPivot('date_achat', 'date_expiration')
                ->withTimestamps();
}
// Ajouter cette propriété pour inclure l'attribut dans les réponses JSON
protected $appends = ['image_url'];

// Ajout de l'accesseur pour l'image
public function getImageUrlAttribute()
{
    if (!$this->image_formation) {
        return null;
    }
    
    // Si c'est déjà une URL complète, la retourner telle quelle
    if (filter_var($this->image_formation, FILTER_VALIDATE_URL)) {
        return $this->image_formation;
    }
    
    // Vérifier si c'est un chemin S3 (commence par 'formations/images')
    if (str_starts_with($this->image_formation, 'formations/images')) {
        // Génère une URL S3 présignée valide pour 30 minutes
        try {
            return Storage::disk('s3')->temporaryUrl($this->image_formation, now()->addMinutes(30));
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la génération de l\'URL S3: ' . $e->getMessage());
            // En cas d'erreur, on continue pour essayer le fallback local
        }
    }
    
    // Fallback pour les anciennes images stockées localement
    return '/storage/' . str_replace('public/', '', $this->image_formation);
}
}
