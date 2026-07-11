<div class="row g-0">
    <div class="col-md-4 d-none d-md-block" style="background: var(--primary-bg); border-right: 1px solid var(--border-color);">
        <div class="p-4 h-100">
            <h6 class="text-uppercase text-muted fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Ticket Details</h6>
            
            <div class="mb-3">
                <label class="text-muted small mb-1">Ticket ID</label>
                <div class="fw-bold fs-6">{{ $ticket->ticket_number }}</div>
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Client</label>
                @if($ticket->client)
                    <div class="fw-medium">{{ $ticket->client->company_name }}</div>
                @else
                    <div class="fw-medium text-muted">Internal Ticket</div>
                @endif
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Category & Priority</label>
                <div>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary me-1">{{ $ticket->category }}</span>
                    @php
                        $pColor = match($ticket->priority) {
                            'Urgent' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }}">{{ $ticket->priority }}</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Status</label>
                <div>
                    @php
                        $sColor = match($ticket->status) {
                            'Open' => 'info', 'Pending' => 'warning', 'Resolved' => 'success', 'Closed' => 'secondary', default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }}">{{ $ticket->status }}</span>
                </div>
            </div>

            <div>
                <label class="text-muted small mb-1">Assigned To</label>
                <div class="fw-medium">{{ $ticket->assignee->name ?? 'Unassigned' }}</div>
            </div>
            
            <div class="mt-4 pt-4 border-top" style="border-color: var(--border-color) !important;">
                <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-outline-primary w-100">
                    Open Full Ticket <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="modal-header border-bottom px-4 py-3">
            <h5 class="modal-title fw-bold text-truncate">{{ $ticket->subject }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-0" style="height: 500px; overflow-y: auto; background: var(--card-bg);">
            <!-- Original Description -->
            <div class="p-4 border-bottom">
                <div class="d-flex align-items-center mb-3">
                    <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px;">
                        {{ substr($ticket->creator->name ?? 'U', 0, 1) }}
                    </div>
                    <div>
                        <div class="fw-bold small">{{ $ticket->creator->name ?? 'System' }}</div>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                <div class="text-main" style="line-height: 1.6; font-size: 0.9rem;">
                    {!! nl2br(e($ticket->description)) !!}
                </div>
            </div>
            
            <!-- Replies -->
            <div class="p-4" style="background: var(--primary-bg);">
                @if($ticket->replies->count() > 0)
                    @foreach($ticket->replies as $reply)
                        <div class="card shadow-sm border-0 mb-3 {{ $reply->is_internal ? 'border-start border-4 border-warning' : '' }}" style="background: {{ $reply->is_internal ? 'rgba(255,193,7,0.05)' : 'var(--card-bg)' }};">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-{{ $reply->is_internal ? 'warning' : 'secondary' }} bg-opacity-10 text-{{ $reply->is_internal ? 'warning' : 'secondary' }} rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 28px; height: 28px;">
                                            {{ substr($reply->user->name ?? 'S', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold small" style="line-height: 1;">{{ $reply->user->name ?? 'System' }}</div>
                                            <span class="text-muted" style="font-size: 0.65rem;">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    @if($reply->is_internal)
                                        <span class="badge bg-warning text-dark align-self-start" style="font-size: 0.65rem;"><i class="bi bi-lock-fill me-1"></i>Internal</span>
                                    @endif
                                </div>
                                <div class="text-main" style="font-size: 0.9rem; line-height: 1.5;">
                                    {!! nl2br(e($reply->message)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center text-muted py-4">
                        <i class="bi bi-chat-dots fs-3 d-block mb-2 opacity-50"></i>
                        <span class="small">No replies yet.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
