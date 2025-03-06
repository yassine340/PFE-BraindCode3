<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'lecon_id',
        'titre',
        'questions',
        'reponses',
        'noteFinale',
        'dureeMaximale'
    ];

    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }
}
