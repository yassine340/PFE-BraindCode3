<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'url',
        'lecon_id',
    ];

    // Ajouter cet attribut aux réponses JSON
    protected $appends = ['video_url'];

    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }

    /**
     * Génère une URL temporaire pour accéder au fichier sur S3
     */
    public function getVideoUrlAttribute()
    {
        if (!$this->url) {
            return null;
        }

        // Si c'est déjà une URL absolue
        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        }

        try {
            // Générer une URL temporaire valide pendant 30 minutes
            return Storage::disk('s3')->temporaryUrl(
                $this->url,  // Chemin relatif stocké (ex: 'videos/ma-video.mp4')
                now()->addMinutes(30)
            );
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la génération de l'URL S3 pour la vidéo {$this->id}: " . $e->getMessage());
            return null;
        }
    }
}