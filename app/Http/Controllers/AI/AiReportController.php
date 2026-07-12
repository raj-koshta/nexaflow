<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AI\AiService;
use App\Models\Project;
use Carbon\Carbon;

class AiReportController extends Controller
{
    public function index()
    {
        $projects = Project::select('id', 'name')->get();
        return view('ai.reports.index', compact('projects'));
    }

    public function generate(Request $request, AiService $aiService)
    {
        $validated = $request->validate([
            'report_type' => 'required|in:Weekly,Monthly',
            'focus_area' => 'required|string',
            'project_id' => 'nullable|exists:projects,id',
            'custom_instructions' => 'nullable|string'
        ]);

        try {
            $currentDate = Carbon::now()->format('F j, Y');
            
            $systemPrompt = "You are an expert executive analyst. Your task is to generate a beautiful, structured Markdown status report.";
            $userPrompt = "Today's Date: {$currentDate}\nPlease generate a {$validated['report_type']} Status Report.\n";
            $userPrompt .= "Focus Area: {$validated['focus_area']}\n";
            
            if ($validated['focus_area'] === 'Specific Project' && !empty($validated['project_id'])) {
                $project = Project::find($validated['project_id']);
                if ($project) {
                    $userPrompt .= "Project Name: {$project->name}\n";
                }
            }

            if (!empty($validated['custom_instructions'])) {
                $userPrompt .= "Custom Instructions from User: {$validated['custom_instructions']}\n";
            }

            $userPrompt .= "\nFormat the report with clear headings (e.g., Executive Summary, Progress Made, Key Metrics, Blockers, Next Steps) and use checklists and bold text appropriately.";

            $fullPrompt = $systemPrompt . "\n\n" . $userPrompt;
            $result = $aiService->generateResponse($fullPrompt);

            return response()->json([
                'success' => true,
                'report' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating report: ' . $e->getMessage()
            ], 500);
        }
    }
}
