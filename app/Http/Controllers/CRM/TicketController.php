<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\User;
use App\Services\CRM\TicketService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Ticket::with(['client', 'assignee', 'creator']);

            if ($request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('ticket_number', 'like', "%{$search}%")
                      ->orWhere('subject', 'like', "%{$search}%")
                      ->orWhereHas('client', fn($q2) => $q2->where('company_name', 'like', "%{$search}%"));
                });
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }
            if ($request->priority) {
                $query->where('priority', $request->priority);
            }

            $tickets = $query->latest()->paginate(15);
            return view('tickets.partials.table', compact('tickets'))->render();
        }

        $clients = Client::orderBy('company_name')->get();
        $users = User::orderBy('name')->get();
        $tickets = Ticket::with(['client', 'assignee', 'creator'])->latest()->paginate(15);

        return view('tickets.index', compact('clients', 'users', 'tickets'));
    }

    public function show(Request $request, Ticket $ticket)
    {
        $ticket->load(['client', 'assignee', 'creator', 'replies.user']);
        
        if ($request->ajax()) {
            return view('tickets.partials.quick-view', compact('ticket'))->render();
        }
        
        return view('tickets.show', compact('ticket'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Open,Pending,Resolved,Closed',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'category' => 'required|string'
        ]);

        $ticket = $this->ticketService->createTicket($validated);

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully.',
            'ticket' => $ticket
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:Open,Pending,Resolved,Closed',
            'priority' => 'required|in:Low,Medium,High,Urgent',
            'category' => 'required|string'
        ]);

        $ticket = $this->ticketService->updateTicket($ticket, $validated);

        return response()->json([
            'success' => true,
            'message' => 'Ticket updated successfully.',
            'ticket' => $ticket
        ]);
    }

    public function destroy(Ticket $ticket)
    {
        $this->ticketService->deleteTicket($ticket);

        return response()->json([
            'success' => true,
            'message' => 'Ticket deleted successfully.'
        ]);
    }

    /**
     * AI Ticket Assistant: Summarize the ticket thread.
     */
    public function aiSummarize(Request $request, Ticket $ticket, \App\Services\AI\AiService $aiService)
    {
        $ticket->load('replies.user');
        
        $thread = "Subject: " . $ticket->subject . "\n";
        $thread .= "Description: " . $ticket->description . "\n";
        foreach ($ticket->replies as $reply) {
            $thread .= "Reply from " . ($reply->user->name ?? 'System') . ": " . $reply->message . "\n";
        }
        
        $prompt = "Summarize the following support ticket thread into a concise bullet-point summary:\n\n" . $thread;
        $summary = $aiService->generateResponse($prompt);
        
        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'message' => "**[AI Summary]**\n" . $summary,
            'is_internal' => true,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket summarized successfully.'
        ]);
    }

    /**
     * AI Ticket Assistant: Generate a draft reply based on user intent.
     */
    public function aiGenerateReply(Request $request, Ticket $ticket, \App\Services\AI\AiService $aiService)
    {
        $request->validate(['intent' => 'required|string|max:500']);
        
        $thread = "Subject: " . $ticket->subject . "\n";
        $thread .= "Description: " . $ticket->description . "\n";
        if ($ticket->replies()->count() > 0) {
            $lastReply = $ticket->replies()->latest()->first();
            $thread .= "Latest Update: " . $lastReply->message . "\n";
        }
        
        $prompt = "You are a professional customer support agent for NexaFlow. The customer opened this ticket:\n\n" . $thread;
        $prompt .= "\n\nPlease write a polite, professional draft response based on this intent: " . $request->intent;
        
        $draft = $aiService->generateResponse($prompt);
        
        return response()->json([
            'success' => true,
            'draft' => $draft
        ]);
    }
}
