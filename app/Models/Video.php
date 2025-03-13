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
        'formation_id',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
