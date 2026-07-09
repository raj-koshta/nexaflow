<?php

namespace App\Services\CRM;

use App\Models\FollowUp;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FollowUpService
{
    /**
     * Get follow-ups categorized by their date/status.
     */
    public function getCategorizedFollowUps(string $category, array $filters = [], $perPage = 15)
    {
        $query = FollowUp::query()->with(['client', 'lead', 'assignee', 'creator']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('remarks', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('lead', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%");
                  });
            });
        }

        $today = Carbon::today();

        switch ($category) {
            case 'overdue':
                $query->where('status', 'Pending')
                      ->where('follow_date', '<', $today)
                      ->orderBy('follow_date', 'asc');
                break;
            case 'today':
                $query->where('status', 'Pending')
                      ->whereDate('follow_date', $today)
                      ->orderBy('follow_time', 'asc');
                break;
            case 'upcoming':
                $query->where('status', 'Pending')
                      ->where('follow_date', '>', $today)
                      ->orderBy('follow_date', 'asc');
                break;
            case 'completed':
                $query->where('status', 'Completed')
                      ->orderBy('updated_at', 'desc');
                break;
            default: // all pending
                $query->where('status', 'Pending')
                      ->orderBy('follow_date', 'asc');
                break;
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new follow up.
     */
    public function createFollowUp(array $data): FollowUp
    {
        $data['created_by'] = Auth::id();
        if (empty($data['assigned_to'])) {
            $data['assigned_to'] = Auth::id(); // default to creator
        }
        $data['status'] = 'Pending';
        return FollowUp::create($data);
    }

    /**
     * Update an existing follow up.
     */
    public function updateFollowUp(FollowUp $followUp, array $data): FollowUp
    {
        $followUp->update($data);
        return $followUp;
    }
    
    /**
     * Mark a follow up as completed.
     */
    public function completeFollowUp(FollowUp $followUp): FollowUp
    {
        $followUp->update(['status' => 'Completed']);
        return $followUp;
    }

    /**
     * Delete a follow up.
     */
    public function deleteFollowUp(FollowUp $followUp): bool
    {
        return $followUp->delete();
    }
}
