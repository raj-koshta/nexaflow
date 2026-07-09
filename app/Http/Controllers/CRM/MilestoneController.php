<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Milestone;
use App\Services\CRM\MilestoneService;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{
    protected $milestoneService;

    public function __construct(MilestoneService $milestoneService)
    {
        $this->milestoneService = $milestoneService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Pending,In Progress,Completed'
        ]);

        $milestone = $this->milestoneService->createMilestone($validated);

        return response()->json([
            'success' => true,
            'message' => 'Milestone created successfully.',
            'milestone' => $milestone
        ]);
    }

    public function update(Request $request, Milestone $milestone)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:Pending,In Progress,Completed',
            'progress' => 'nullable|integer|min:0|max:100'
        ]);

        $milestone = $this->milestoneService->updateMilestone($milestone, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Milestone updated successfully.',
            'milestone' => $milestone
        ]);
    }

    public function destroy(Milestone $milestone)
    {
        $this->milestoneService->deleteMilestone($milestone);

        return response()->json([
            'success' => true,
            'message' => 'Milestone deleted successfully.'
        ]);
    }
}
