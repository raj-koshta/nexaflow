<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiPromptTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'system_prompt',
        'user_prompt',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}



