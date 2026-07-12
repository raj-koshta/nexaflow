<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AI\AiService;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;

class AiBusinessInsightsController extends Controller
{
    public function index()
    {
        // Gather KPI Data for the view
        $kpi = [
            'active_projects' => Project::where('status', '!=', 'Completed')->count(),
            'overdue_tasks' => Task::where('status', '!=', 'Done')->where('due_date', '<', now())->count(),
            'open_tickets' => Ticket::where('status', 'open')->count(),
            'revenue_ytd' => '$124,500' // Mocked revenue
        ];
        
        return view('ai.insights.index', compact('kpi'));
    }

    public function generate(Request $request, AiService $aiService)
    {
        $validated = $request->validate([
            'kpi_data' => 'required|array'
        ]);

        try {
            $systemPrompt = "You are an elite Business Strategy Analyst and AI Assistant for the NexaFlow CRM. Analyze the provided company KPIs and generate 3 to 4 actionable, strategic business insights in a beautiful Markdown format.";
            $userPrompt = "Please generate business insights based on these current KPIs:\n";
            
            foreach ($validated['kpi_data'] as $key => $value) {
                $userPrompt .= "- " . ucwords(str_replace('_', ' ', $key)) . ": " . $value . "\n";
            }
            
            $userPrompt .= "\nProvide strategic recommendations based on these numbers.";

            $fullPrompt = $systemPrompt . "\n\n" . $userPrompt;
            $result = $aiService->generateResponse($fullPrompt);

            return response()->json([
                'success' => true,
                'insights' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating insights: ' . $e->getMessage()
            ], 500);
        }
    }
}
