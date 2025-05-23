<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGlobalGamification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_points', 'global_level', 'badges'];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
