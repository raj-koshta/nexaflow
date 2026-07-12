<?php

namespace App\Services\AI;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    /**
     * Generates an AI response for the given prompt.
     */
    public function generateResponse(string $prompt): string
    {
        $geminiKey = env('GEMINI_API_KEY');
        $openAiKey = env('OPENAI_API_KEY');

        // Prefer Gemini (Free), fallback to OpenAI if set, else mock
        if (!empty($geminiKey)) {
            return $this->callGemini($geminiKey, $prompt);
        } elseif (!empty($openAiKey)) {
            return $this->callOpenAI($openAiKey, $prompt);
        }

        return $this->smartMockResponse($prompt);
    }

    private function callGemini(string $apiKey, string $prompt): string
    {
        try {
            $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "You are NexaFlow AI, a helpful CRM assistant. Provide concise, professional responses formatted in markdown. Here is the user's prompt: \n\n" . $prompt]
                        ]
                    ]
                ]
            ]);

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text') ?? "Sorry, I couldn't generate a response.";
            }

            Log::error('Gemini API Error: ' . $response->body());
            return "Sorry, the Gemini AI service returned an error. Make sure your GEMINI_API_KEY is valid.";

        } catch (\Exception $e) {
            Log::error('Gemini API Exception: ' . $e->getMessage());
            return "Sorry, I couldn't connect to the Gemini service. " . $e->getMessage();
        }
    }

    private function callOpenAI(string $apiKey, string $prompt): string
    {
        try {
            $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => "You are NexaFlow AI, a helpful CRM assistant. Provide concise, professional responses formatted in markdown."],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            if ($response->successful()) {
                return $response->json('choices.0.message.content') ?? "Sorry, I couldn't generate a response.";
            }

            Log::error('OpenAI API Error: ' . $response->body());
            return "Sorry, the OpenAI service returned an error.";

        } catch (\Exception $e) {
            Log::error('OpenAI API Exception: ' . $e->getMessage());
            return "Sorry, I couldn't connect to the OpenAI service.";
        }
    }

    /**
     * Mocks an AI response for the given prompt if no API key is available.
     */
    private function smartMockResponse(string $prompt): string
    {
        // Simulate processing time
        sleep(1);
        $prompt = strtolower($prompt);

        // Intent: Summarize open tickets
        if (Str::contains($prompt, ['summarize', 'open tickets', 'my tickets'])) {
            $tickets = \App\Models\Ticket::where('status', 'open')->latest()->take(5)->get();
            if ($tickets->isEmpty()) {
                return "You currently have no open tickets! Great job staying on top of your support queue.";
            }
            
            $response = "Here is a summary of your open tickets:\n\n";
            foreach ($tickets as $ticket) {
                $response .= "- **" . $ticket->subject . "** (Priority: " . ucfirst($ticket->priority) . ")\n";
            }
            return $response . "\nWould you like me to draft a reply for any of these?";
        }
        
        // Intent: Apology email
        if (Str::contains($prompt, ['apology', 'delay', 'draft'])) {
            return "Here is a draft apology email you can use:\n\n**Subject:** Update regarding your recent request\n\nDear [Client Name],\n\nI sincerely apologize for the delay in getting back to you. We've been experiencing a high volume of requests, but I want to assure you that your issue is a priority for us. We are currently working on it and expect to have an update for you by tomorrow.\n\nThank you for your patience and understanding.\n\nBest regards,\n" . \Illuminate\Support\Facades\Auth::user()->name;
        }

        // Intent: Task checklist
        if (Str::contains($prompt, ['checklist', 'website redesign', 'task'])) {
            return "Here is a recommended checklist for a website redesign project:\n\n1. **Discovery & Planning** (Define goals, target audience, sitemap)\n2. **Wireframing & UI/UX Design** (Create mockups, finalize color scheme)\n3. **Content Creation** (Draft copy, gather high-res images)\n4. **Development** (Setup staging, build templates, integrate CMS)\n5. **Testing** (Mobile responsiveness, cross-browser, QA)\n6. **Launch & Handover** (Go live, monitor performance)\n\nI can automatically create these as tasks in a new Project if you'd like.";
        }

        // Intent: AI Task Generator
        if (Str::contains($prompt, ['raw json array'])) {
            return '[
                {"title": "Requirement Gathering", "description": "Meet with stakeholders to understand the core requirements and project scope.", "priority": "High", "estimated_hours": 4},
                {"title": "UI/UX Design", "description": "Design wireframes and high-fidelity mockups for the main screens.", "priority": "High", "estimated_hours": 12},
                {"title": "Database Schema", "description": "Design the initial database schema and migrations.", "priority": "Medium", "estimated_hours": 6},
                {"title": "Frontend Development", "description": "Implement the design using the chosen frontend framework.", "priority": "Medium", "estimated_hours": 20},
                {"title": "Backend Integration", "description": "Connect frontend forms and pages to the backend APIs.", "priority": "High", "estimated_hours": 15},
                {"title": "QA & Testing", "description": "Perform comprehensive testing across browsers and devices.", "priority": "Urgent", "estimated_hours": 8}
            ]';
        }

        // Intent: AI Email Generator
        if (Str::contains($prompt, ['expert copywriter and professional assistant'])) {
            return "Subject: Update Regarding Your Recent Request\n\nDear Client,\n\nI hope this email finds you well.\n\nI am writing to follow up on your recent request. We have reviewed the details and our team is currently working on the implementation. We anticipate having a preliminary version ready for your review by the end of this week.\n\nPlease let us know if you have any additional questions or if there is anything else we can assist you with in the meantime.\n\nBest regards,\n\nYour NexaFlow Team";
        }
        
        // Intent: AI Meeting Notes
        if (Str::contains($prompt, ['generate meeting notes based on this transcript', 'expert executive assistant'])) {
            return "### 📝 Meeting Summary\n\nThe team discussed the upcoming Q3 product launch. Main focus areas were finalizing the marketing budget, aligning on the new UI mockup dates, and ensuring the backend infrastructure is ready for the expected traffic spike. The overall sentiment was positive and on-track.\n\n### ✅ Action Items\n\n- [ ] Finalize the marketing budget for Q3 (Marketing Team)\n- [ ] Approve the new UI mockups (Design Team)\n- [ ] Run stress tests on the backend servers (Engineering Team)\n- [ ] Schedule follow-up sync for next Thursday\n\n### ⏰ Deadlines\n\n- **Marketing Budget:** Next Friday\n- **UI Mockups:** End of the month\n- **Stress Tests:** Week of the 15th";
        }
        
        // Intent: AI Business Insights
        if (Str::contains($prompt, ['elite business strategy analyst', 'generate business insights'])) {
            return "### 📊 Strategic Business Analysis\n\nBased on your current KPIs, here are your strategic insights for the week:\n\n**1. High Volume of Overdue Tasks**\nWith several overdue tasks, your delivery pipeline is currently bottlenecked. *Recommendation:* Consider running a quick standup meeting to unblock team members or reassign tasks from overloaded developers.\n\n**2. Support Queue Warning**\nYou have multiple open tickets. *Recommendation:* Reallocate one team member to support duty for the next 48 hours to bring the ticket queue back to inbox zero and maintain your SLA.\n\n**3. Revenue Trajectory**\nYour YTD Revenue of $124,500 is strong! *Recommendation:* You have the budget to safely scale. Consider hiring a junior support agent to permanently resolve the support queue issue.\n\n**Summary:** Focus on delivery and unblocking this week over taking on new active projects.";
        }

        // Intent: AI Ticket Assistant - Summarize
        if (Str::contains($prompt, ['summarize the following support ticket thread'])) {
            return "Here is a quick summary of this ticket:\n\n- The user reported an issue or made a request.\n- Initial troubleshooting was attempted.\n- The client is waiting for a resolution or further update.\n\n*(Note: Add GEMINI_API_KEY to generate a real context-aware summary!)*";
        }

        // Intent: AI Ticket Assistant - Reply
        if (Str::contains($prompt, ['draft response based on this intent'])) {
            return "Hi there,\n\nThank you for reaching out! I want to let you know that we have received your ticket and are currently reviewing it based on your latest update.\n\nWe will get back to you very soon with a solution.\n\nBest regards,\nSupport Team\n\n*(Note: Add GEMINI_API_KEY to generate a real response!)*";
        }

        if (Str::contains($prompt, ['hello', 'hi', 'hey'])) {
            return "Hello " . \Illuminate\Support\Facades\Auth::user()->name . "! I am NexaFlow's AI Assistant. How can I help you manage your business today?\n\n*(Note: I am running in mock mode. Add GEMINI_API_KEY to your .env file to enable real AI responses!)*";
        }

        // Fallback context-aware response
        return "I understand you're asking about: *" . $prompt . "*. As an AI Assistant in NexaFlow, I'm currently running in Mock Mode.\n\n**To get real free AI answers:**\n1. Get a free Google Gemini key from Google AI Studio.\n2. Add `GEMINI_API_KEY=your_key` to `.env`.\n3. Try asking me this again!";
    }
}
