<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\CRM\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);

        $query = Task::with(['project', 'assignee', 'milestone'])->latest();

        if ($request->has('trashed') && $request->trashed == '1') {
            $query->onlyTrashed();
        }

        $tasks = $query->get();
            
        $projects = \App\Models\Project::select('id', 'name')->get();
            
        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function show(Request $request, Task $task)
    {
        Gate::authorize('view', $task);

        $task->load(['project', 'assignee', 'milestone', 'creator']);
        
        if ($request->ajax()) {
            return view('tasks.partials.quick-view', compact('task'))->render();
        }
        
        // If we ever want a standalone task page
        return view('tasks.show', compact('task'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Task::class);

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:Todo,In Progress,Review,Done',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
        ]);

        $task = $this->taskService->createTask($validated);
        $task->load('assignee');

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully.',
            'task' => $task
        ]);
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $validated = $request->validate([
            'milestone_id' => 'nullable|exists:milestones,id',
            'assigned_to' => 'nullable|exists:users,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'sometimes|required|in:Todo,In Progress,Review,Done',
            'priority' => 'sometimes|required|in:Low,Medium,High,Urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $task = $this->taskService->updateTask($task, $validated);
        $task->load('assignee');

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully.',
            'task' => $task
        ]);
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        Gate::authorize('delete', Task::class);
        $request->validate(['ids' => 'required|array']);
        try {
            $count = $this->taskService->bulkDelete($request->ids);
            return response()->json(['success' => true, 'message' => "$count tasks deleted successfully."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error deleting tasks: ' . $e->getMessage()], 500);
        }
    }

    public function bulkUpdate(Request $request)
    {
        Gate::authorize('update', Task::class);
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|string'
        ]);
        try {
            $count = $this->taskService->bulkUpdate($request->ids, ['status' => $request->status]);
            return response()->json(['success' => true, 'message' => "$count tasks updated successfully."]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error updating tasks: ' . $e->getMessage()], 500);
        }
    }

    public function restore($id)
    {
        Gate::authorize('restore', Task::class);
        $task = Task::withTrashed()->findOrFail($id);
        $task->restore();
        return response()->json(['success' => true, 'message' => 'Task restored successfully.']);
    }

    public function forceDelete($id)
    {
        Gate::authorize('forceDelete', Task::class);
        $task = Task::withTrashed()->findOrFail($id);
        $task->forceDelete();
        return response()->json(['success' => true, 'message' => 'Task permanently deleted.']);
    }
}
