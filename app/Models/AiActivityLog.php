<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'feature_name',
        'prompt',
        'response',
        'processing_time',
        'is_successful',
        'error_message',
    ];

    protected $casts = [
        'is_successful' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
