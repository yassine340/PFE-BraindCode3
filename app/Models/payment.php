<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Formation;

class Payment extends Model
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
    
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}