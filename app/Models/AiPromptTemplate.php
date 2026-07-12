<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPromptTemplate extends Model
{
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
