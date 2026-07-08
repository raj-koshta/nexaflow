<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    /** @use HasFactory<\Database\Factories\LeadFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_code',
        'name',
        'email',
        'phone',
        'company',
        'source',
        'status',
        'priority',
        'expected_value',
        'remarks',
        'assigned_to',
        'created_by',
    ];

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
