<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoProgress extends Model
{
    use HasFactory;
    
    // Spécifier explicitement le nom de la table
    protected $table = 'video_progresses';
    
    protected $fillable = [
        'user_id',
        'video_id',
        'current_time',
        'completed'
    ];
    
    protected $casts = [
        'completed' => 'boolean',
    ];
    
    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function video()
    {
        return $this->belongsTo(Video::class);
    }
}