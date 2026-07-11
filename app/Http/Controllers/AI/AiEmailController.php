<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\AI\AiService;
use Illuminate\Support\Facades\Mail;
use App\Mail\GeneratedEmail;

class AiEmailController extends Controller
{
    public function index()
    {
        return view('ai.email.index');
    }

    public function generate(Request $request, AiService $aiService)
    {
        $validated = $request->validate([
            'recipient' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
            'purpose' => 'required|string|max:2000',
            'tone' => 'required|string',
            'length' => 'required|string',
            'language' => 'required|string',
        ]);

        $prompt = "You are an expert copywriter and professional assistant. Write an email with the following specifications:\n";
        
        if (!empty($validated['recipient'])) {
            $prompt .= "- Recipient: " . $validated['recipient'] . "\n";
        }
        if (!empty($validated['subject'])) {
            $prompt .= "- Subject/Topic: " . $validated['subject'] . "\n";
        }
        
        $prompt .= "- Purpose: " . $validated['purpose'] . "\n";
        $prompt .= "- Tone: " . $validated['tone'] . "\n";
        $prompt .= "- Length: " . $validated['length'] . "\n";
        $prompt .= "- Language: " . $validated['language'] . "\n\n";
        $prompt .= "Return ONLY the email content. Do NOT include any markdown code blocks (like ```). Include a Subject line if one wasn't explicitly provided, prefixed with 'Subject: '. The rest of the output should just be the email body.";

        try {
            $emailContent = $aiService->generateResponse($prompt);
            
            // Clean up any markdown blocks if the AI ignored the instruction
            $emailContent = preg_replace('/^```(?:text)?\s*/', '', $emailContent);
            $emailContent = preg_replace('/```$/', '', $emailContent);
            $emailContent = trim($emailContent);

            return response()->json([
                'success' => true,
                'email' => $emailContent
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating email: ' . $e->getMessage()
            ], 500);
        }
    }

    public function send(Request $request)
    {
        $validated = $request->validate([
            'email_to' => 'required|email',
            'email_subject' => 'nullable|string|max:255',
            'email_body' => 'required|string'
        ]);

        try {
            Mail::to($validated['email_to'])->send(
                new GeneratedEmail($validated['email_subject'], $validated['email_body'])
            );

            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ], 500);
        }
    }
}
