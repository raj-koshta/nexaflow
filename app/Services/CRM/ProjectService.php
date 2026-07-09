<?php

namespace App\Services\CRM;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class ProjectService
{
    /**
     * Generate a unique project code (e.g., PROJ-0001).
     */
    public function generateProjectCode(): string
    {
        $lastProject = Project::orderBy('id', 'desc')->first();
        $nextId = $lastProject ? $lastProject->id + 1 : 1;
        return 'PROJ-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get paginated projects with filters.
     */
    public function getProjects(array $filters = [], $perPage = 15)
    {
        $query = Project::query()->with('client');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('project_code', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Create a new project.
     */
    public function createProject(array $data): Project
    {
        $data['project_code'] = $this->generateProjectCode();
        $data['created_by'] = Auth::id();
        $data['progress'] = 0;
        
        if ($data['status'] === 'Completed') {
            $data['completed_at'] = now();
            $data['progress'] = 100;
        }

        return Project::create($data);
    }

    /**
     * Update an existing project.
     */
    public function updateProject(Project $project, array $data): Project
    {
        $data['updated_by'] = Auth::id();
        
        if (isset($data['status'])) {
            if ($data['status'] === 'Completed' && $project->status !== 'Completed') {
                $data['completed_at'] = now();
                $data['progress'] = 100;
            } elseif ($data['status'] !== 'Completed' && $project->status === 'Completed') {
                $data['completed_at'] = null;
            }
        }

        $project->update($data);
        return $project;
    }

    /**
     * Delete a project.
     */
    public function deleteProject(Project $project): bool
    {
        return $project->delete();
    }
}
