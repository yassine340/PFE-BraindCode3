<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'url',
        'formation_id',
    ];

    /**
     * Get the formation that owns the document.
     */
    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }
}
