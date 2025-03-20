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
        'category_id'
    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
    public function lecons()
    {
        return $this->hasMany(Lecon::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
