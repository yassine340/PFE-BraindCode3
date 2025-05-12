<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'startup_id',
        'image_path', // Path to the image
    ];

    /**
     * Get the startup that owns the group.
     */
    public function startup()
    {
        return $this->belongsTo(User::class, 'startup_id');
    }

    /**
     * Get the workers that belong to the group.
     */
    public function workers()
    {
        return $this->belongsToMany(User::class, 'group_worker', 'group_id', 'worker_id')
                    ->where('role', 'worker')
                    ->withTimestamps();
    }
}