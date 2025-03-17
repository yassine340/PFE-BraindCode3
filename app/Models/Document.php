<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'url',
        'lecon_id',
    ];

    /**
     * Get the formation that owns the document.
     */
    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }
}
