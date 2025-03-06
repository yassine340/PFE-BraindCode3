<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ressource extends Model
{
    protected $fillable = [
        'titre',
        'url',
        'type',
        'lecon_id',
        'contenu',
    ];

    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }
}
