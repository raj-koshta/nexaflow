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

    public function show(Ticket $ticket)
    {
        $ticket->load(['client', 'assignee', 'creator', 'replies.user']);
        
        // We will need users for reassignment dropdowns if we add them later, 
        // but for now we just show the ticket details.
        
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
}
