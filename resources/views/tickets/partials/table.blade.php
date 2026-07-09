@if($tickets->count() > 0)
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Ticket Details</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Client</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Category & Priority</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="border-bottom-0 text-end text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-3">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-sm me-3 bg-secondary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--text-main);">
                                        <i class="bi bi-ticket-detailed fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-truncate" style="max-width: 250px;">{{ $ticket->subject }}</h6>
                                        <div class="small text-muted">
                                            <span class="fw-bold text-main">{{ $ticket->ticket_number }}</span>
                                            &bull; {{ $ticket->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($ticket->client)
                                    <div class="d-flex align-items-center mb-1">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 20px; height: 20px; font-size: 0.65rem;">
                                            {{ substr($ticket->client->company_name, 0, 1) }}
                                        </div>
                                        <span class="fw-medium">{{ $ticket->client->company_name }}</span>
                                    </div>
                                @else
                                    <span class="text-muted font-italic small">Internal</span>
                                @endif
                                
                                @if($ticket->assignee)
                                    <div class="small text-muted">
                                        <i class="bi bi-person me-1"></i> Assigned: {{ $ticket->assignee->name }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 align-items-start">
                                    @php
                                        $cColor = match($ticket->category) {
                                            'Bug' => 'danger',
                                            'Feature' => 'info',
                                            'Billing' => 'success',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $cColor }} bg-opacity-10 text-{{ $cColor }} px-2" style="font-size: 0.7rem;">
                                        {{ $ticket->category }}
                                    </span>
                                    
                                    @php
                                        $pColor = match($ticket->priority) {
                                            'Urgent' => 'danger',
                                            'High' => 'warning',
                                            'Medium' => 'info',
                                            'Low' => 'secondary',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded-pill px-2" style="font-size: 0.65rem;">
                                        {{ strtoupper($ticket->priority) }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $sColor = match($ticket->status) {
                                        'Open' => 'info',
                                        'Pending' => 'warning',
                                        'Resolved' => 'success',
                                        'Closed' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} border border-{{ $sColor }} border-opacity-25 rounded-pill px-3 py-2">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-link text-primary p-1" title="View Ticket Details">
                                    <i class="bi bi-eye fs-5"></i>
                                </a>
                                <button class="btn btn-sm btn-link text-muted edit-ticket-btn p-1" data-ticket="{{ json_encode($ticket) }}" title="Edit Ticket">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-ticket-btn p-1" data-id="{{ $ticket->id }}" title="Delete Ticket">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: var(--border-color) !important;">
                <div class="text-muted small">
                    Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} entries
                </div>
                <div>
                    {{ $tickets->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                <i class="bi bi-ticket-detailed"></i>
            </div>
            <h4 class="fw-bold">No tickets found</h4>
            <p class="text-muted mb-4">Start managing support requests by creating your first ticket.</p>
            <button class="btn btn-primary px-4" onclick="openTicketOffcanvas()">
                <i class="bi bi-plus-lg me-2"></i>Create Ticket
            </button>
        </div>
    </div>
@endif
