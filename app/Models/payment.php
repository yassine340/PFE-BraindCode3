<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    protected $fillable = [
        'user_id',
        'formation_id',
        'montant',
        'methode',
        'status',
        'date',
        'confirmation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
