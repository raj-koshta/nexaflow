<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity.
     *
     * @param string $action
     * @param string|null $description
     * @param \Illuminate\Database\Eloquent\Model|null $subject
     */
    public function log(string $action, string $description = null, $subject = null)
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'description' => $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
