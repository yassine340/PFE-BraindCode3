<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Formation extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'prix',
        'estcertifiante',
    ];

    public function formateurs()
    {
        return $this->belongsToMany(user::class, 'formation_formateur');
    }
}
