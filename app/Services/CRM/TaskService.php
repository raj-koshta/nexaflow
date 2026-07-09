<?php

namespace App\Services\CRM;

use App\Models\Task;
use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    public function createTask(array $data): Task
    {
        $data['created_by'] = Auth::id();
        $data['progress'] = 0;
        
        if ($data['status'] === 'Done') {
            $data['completed_at'] = now();
            $data['progress'] = 100;
        }

        $task = Task::create($data);
        $this->updateMilestoneProgress($task->milestone_id);
        
        return $task;
    }

    public function updateTask(Task $task, array $data): Task
    {
        if (isset($data['status'])) {
            if ($data['status'] === 'Done' && $task->status !== 'Done') {
                $data['completed_at'] = now();
                $data['progress'] = 100;
            } elseif ($data['status'] !== 'Done' && $task->status === 'Done') {
                $data['completed_at'] = null;
                $data['progress'] = 99; // just back it off a bit
            }
        }

        $task->update($data);
        $this->updateMilestoneProgress($task->milestone_id);
        
        return $task;
    }

    public function deleteTask(Task $task): bool
    {
        $milestoneId = $task->milestone_id;
        $deleted = $task->delete();
        $this->updateMilestoneProgress($milestoneId);
        
        return $deleted;
    }
    
    protected function updateMilestoneProgress($milestoneId)
    {
        if (!$milestoneId) return;
        
        $milestone = Milestone::find($milestoneId);
        if (!$milestone) return;
        
        $tasks = $milestone->tasks;
        if ($tasks->count() === 0) {
            $milestone->update(['progress' => 0]);
            return;
        }
        
        $totalProgress = $tasks->sum('progress');
        $averageProgress = round($totalProgress / $tasks->count());
        
        $milestone->update([
            'progress' => $averageProgress,
            'status' => $averageProgress === 100 ? 'Completed' : 'In Progress'
        ]);
        
        // Optionally update Project progress here
        $this->updateProjectProgress($milestone->project_id);
    }
    
    protected function updateProjectProgress($projectId)
    {
        $project = Project::find($projectId);
        if (!$project) return;
        
        // A simple project progress calculation: average of all milestones
        $milestones = $project->milestones;
        if ($milestones->count() > 0) {
            $avg = round($milestones->sum('progress') / $milestones->count());
            $project->update(['progress' => $avg]);
        }
    }
}
