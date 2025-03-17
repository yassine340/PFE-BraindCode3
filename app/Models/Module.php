<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'ordre',
        'duree_estimee',
        'formation_id',
    ];

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
    public function lecons()
    {
        return $this->hasMany(Lecon::class);
    }
}
