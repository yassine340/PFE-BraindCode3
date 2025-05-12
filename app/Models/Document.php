<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'url',
        'lecon_id',
    ];

    // Ajouter cet attribut aux réponses JSON
    protected $appends = ['document_url'];

    /**
     * Get the formation that owns the document.
     */
    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }

    /**
     * Génère une URL temporaire pour accéder au document sur S3
     */
    public function getDocumentUrlAttribute()
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
                $this->url,  // Chemin relatif stocké (ex: 'documents/mon-pdf.pdf')
                now()->addMinutes(30)
            );
        } catch (\Exception $e) {
            \Log::error("Erreur lors de la génération de l'URL S3 pour le document {$this->id}: " . $e->getMessage());
            return null;
        }
    }
}