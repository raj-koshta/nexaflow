<?php

namespace App\Services\CRM;

use App\Models\Milestone;
use Illuminate\Support\Facades\Auth;

class MilestoneService
{
    public function createMilestone(array $data): Milestone
    {
        $data['created_by'] = Auth::id();
        $data['progress'] = 0;
        
        if ($data['status'] === 'Completed') {
            $data['progress'] = 100;
        }

        return Milestone::create($data);
    }

    public function updateMilestone(Milestone $milestone, array $data): Milestone
    {
        if (isset($data['status'])) {
            if ($data['status'] === 'Completed') {
                $data['progress'] = 100;
            } elseif ($milestone->status === 'Completed' && $data['status'] !== 'Completed') {
                // If it was completed but is now reopened, keep progress or reset? 
                // Let's rely on tasks to recalculate progress later, but for now set to 99 if manually reopened
                $data['progress'] = $milestone->progress == 100 ? 99 : $milestone->progress;
            }
        }

        $milestone->update($data);
        return $milestone;
    }

    public function deleteMilestone(Milestone $milestone): bool
    {
        return $milestone->delete();
    }
}
