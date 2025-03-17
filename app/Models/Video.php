<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'url',
        'lecon_id',
    ];

    public function lecon()
    {
        return $this->belongsTo(Lecon::class);
    }
}
