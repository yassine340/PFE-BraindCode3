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
        'image_formation',
    ];

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
