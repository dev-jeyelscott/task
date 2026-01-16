<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'is_completed',
        'completed_at',
        'priority',
        'severity',
        'due_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'due_at' => 'datetime',
    ];
}
