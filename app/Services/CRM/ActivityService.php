<?php

namespace App\Services\CRM;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    /**
     * Get a paginated timeline of activities with optional filters.
     */
    public function getTimeline(array $filters = [], $perPage = 15)
    {
        $query = Activity::query()->with(['client', 'lead', 'creator'])->orderBy('activity_date', 'desc');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lead', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['client_id'])) {
            $query->where('client_id', $filters['client_id']);
        }

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }
        
        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new activity.
     */
    public function createActivity(array $data): Activity
    {
        $data['created_by'] = Auth::id();
        return Activity::create($data);
    }

    /**
     * Update an existing activity.
     */
    public function updateActivity(Activity $activity, array $data): Activity
    {
        $activity->update($data);
        return $activity;
    }

    /**
     * Delete an activity.
     */
    public function deleteActivity(Activity $activity): bool
    {
        return $activity->delete();
    }
}
