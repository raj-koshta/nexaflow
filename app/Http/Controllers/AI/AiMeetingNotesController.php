<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AI\AiService;

class AiMeetingNotesController extends Controller
{
    public function index()
    {
        $projects = \App\Models\Project::select('id', 'name')->get();
        return view('ai.meetings.index', compact('projects'));
    }

    public function generate(Request $request, AiService $aiService)
    {
        $validated = $request->validate([
            'transcript' => 'required|string',
            'meeting_title' => 'nullable|string|max:255',
            'attendees' => 'nullable|string|max:255',
            'date' => 'nullable|date',
        ]);

        try {
            $systemPrompt = "You are an expert executive assistant. Take the following meeting transcript/notes and generate a beautiful, structured Markdown summary.";
            $userPrompt = "Please generate meeting notes based on this transcript. Format the output with clear headings: # Summary, # Action Items (as a checklist), and # Deadlines.\n\n";
            
            if (!empty($validated['meeting_title'])) {
                $userPrompt .= "Meeting Title: " . $validated['meeting_title'] . "\n";
            }
            if (!empty($validated['date'])) {
                $userPrompt .= "Date: " . $validated['date'] . "\n";
            }
            if (!empty($validated['attendees'])) {
                $userPrompt .= "Attendees: " . $validated['attendees'] . "\n";
            }
            
            $userPrompt .= "\nTranscript:\n" . $validated['transcript'];

            $fullPrompt = $systemPrompt . "\n\n" . $userPrompt;
            $result = $aiService->generateResponse($fullPrompt);

            return response()->json([
                'success' => true,
                'notes' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating meeting notes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeTasks(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'tasks' => 'required|array',
            'tasks.*' => 'required|string|max:255',
        ]);

        try {
            $createdTasks = 0;
            foreach ($validated['tasks'] as $taskTitle) {
                // Remove Markdown checkbox syntax if present, e.g., "- [ ] " or "* [ ] "
                $cleanTitle = preg_replace('/^[-*]\s*\[\s*[xX]?\s*\]\s*/', '', $taskTitle);
                $cleanTitle = trim($cleanTitle);
                
                if (!empty($cleanTitle)) {
                    \App\Models\Task::create([
                        'title' => $cleanTitle,
                        'project_id' => $validated['project_id'],
                        'status' => 'Todo',
                        'priority' => 'Medium',
                        'created_by' => auth()->id(),
                    ]);
                    $createdTasks++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully created $createdTasks tasks!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating tasks: ' . $e->getMessage()
            ], 500);
        }
    }
}
