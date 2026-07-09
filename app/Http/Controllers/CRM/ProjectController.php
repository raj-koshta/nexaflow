<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Client;
use App\Services\CRM\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Project::class);

        $filters = $request->only(['search', 'status', 'priority']);
        $projects = $this->projectService->getProjects($filters);
        
        $clients = Client::select('id', 'company_name')->orderBy('company_name')->get();

        if ($request->ajax()) {
            return view('projects.partials.table', compact('projects'))->render();
        }

        return view('projects.index', compact('projects', 'clients'));
    }

    public function store(StoreProjectRequest $request)
    {
        Gate::authorize('create', Project::class);

        try {
            DB::beginTransaction();
            $project = $this->projectService->createProject($request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project created successfully.',
                'project' => $project
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Project $project)
    {
        Gate::authorize('view', $project);
        
        $project->load([
            'client', 
            'creator', 
            'milestones' => function($q) { $q->orderBy('due_date', 'asc'); },
            'tasks.assignee',
            'tasks.milestone'
        ]);
        
        $users = \App\Models\User::orderBy('name')->get();

        return view('projects.show', compact('project', 'users'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        Gate::authorize('update', $project);

        try {
            DB::beginTransaction();
            $project = $this->projectService->updateProject($project, $request->validated());
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully.',
                'project' => $project
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating project: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        try {
            $this->projectService->deleteProject($project);
            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting project: ' . $e->getMessage()
            ], 500);
        }
    }
}
