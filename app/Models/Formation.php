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
        'category_id',
        'est_valide', // Ajout du nouveau champ
        'est_publiee', // Ajout du champ de publication
        'user_id'  // Ajout du champ user_id pour le formateur

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
      // Relation avec l'utilisateur (formateur)
      public function user()
      {
          return $this->belongsTo(User::class);
      }
}
