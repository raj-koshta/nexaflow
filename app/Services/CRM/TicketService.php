<?php

namespace App\Services\CRM;

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TicketService
{
    public function createTicket(array $data): Ticket
    {
        $data['created_by'] = Auth::id();
        $data['ticket_number'] = $this->generateTicketNumber();
        
        return Ticket::create($data);
    }

    public function updateTicket(Ticket $ticket, array $data): Ticket
    {
        $ticket->update($data);
        return $ticket;
    }

    public function deleteTicket(Ticket $ticket): bool
    {
        return $ticket->delete();
    }

    protected function generateTicketNumber(): string
    {
        $lastTicket = Ticket::withTrashed()->orderBy('id', 'desc')->first();
        $lastId = $lastTicket ? $lastTicket->id : 0;
        return 'TKT-' . str_pad($lastId + 1, 5, '0', STR_PAD_LEFT); // e.g., TKT-00001
    }
}
