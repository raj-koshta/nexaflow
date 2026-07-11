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

    /**
     * AI Task Generator: Generate tasks based on intent.
     */
    public function aiGenerateTasks(Request $request, Project $project, \App\Services\AI\AiService $aiService)
    {
        $request->validate(['intent' => 'required|string|max:1000']);
        
        $prompt = "You are a professional project manager. Break down the following project into actionable tasks.\n";
        $prompt .= "Project Name: " . $project->name . "\n";
        $prompt .= "Project Context / Goal: " . $request->intent . "\n\n";
        $prompt .= "Return ONLY a raw JSON array of objects. Each object must have the following keys:\n";
        $prompt .= "- 'title' (string, max 255 chars)\n";
        $prompt .= "- 'description' (string)\n";
        $prompt .= "- 'priority' (string: 'Low', 'Medium', 'High', or 'Urgent')\n";
        $prompt .= "- 'estimated_hours' (integer)\n\n";
        $prompt .= "Do not include any markdown formatting, code blocks, or text outside of the JSON array.";

        $jsonStr = $aiService->generateResponse($prompt);
        
        // Clean up potential markdown formatting from AI just in case
        $jsonStr = str_replace(['```json', '```'], '', $jsonStr);
        $jsonStr = trim($jsonStr);

        $tasks = json_decode($jsonStr, true);

        if (!$tasks || !is_array($tasks)) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to parse AI response. Please try again.',
                'raw' => $jsonStr
            ], 500);
        }

        DB::beginTransaction();
        try {
            foreach ($tasks as $taskData) {
                $project->tasks()->create([
                    'title' => substr($taskData['title'] ?? 'Generated Task', 0, 255),
                    'description' => $taskData['description'] ?? '',
                    'priority' => in_array($taskData['priority'] ?? '', ['Low', 'Medium', 'High', 'Urgent']) ? $taskData['priority'] : 'Medium',
                    'estimated_hours' => $taskData['estimated_hours'] ?? 0,
                    'status' => 'Todo',
                    'created_by' => auth()->id(),
                    'progress' => 0
                ]);
            }
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($tasks) . ' tasks generated successfully!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving generated tasks: ' . $e->getMessage()
            ], 500);
        }
    }
}
