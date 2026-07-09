<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\CRM\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function store(Request $request)
    {
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
        $this->taskService->deleteTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.'
        ]);
    }
}
