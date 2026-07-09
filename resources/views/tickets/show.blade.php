@extends('layouts.master')

@section('title', 'Ticket ' . $ticket->ticket_number)

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <a href="{{ route('tickets.index') }}" class="text-decoration-none text-muted d-flex align-items-center">
        <i class="bi bi-arrow-left me-2"></i> Back to Tickets
    </a>
    <div class="text-end">
        @php
            $sColor = match($ticket->status) {
                'Open' => 'info', 'Pending' => 'warning', 'Resolved' => 'success', 'Closed' => 'secondary', default => 'secondary'
            };
            $pColor = match($ticket->priority) {
                'Urgent' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
            };
        @endphp
        <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} border border-{{ $sColor }} border-opacity-25 rounded-pill px-3 py-2 me-2">
            Status: {{ $ticket->status }}
        </span>
        <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded-pill px-3 py-2">
            Priority: {{ $ticket->priority }}
        </span>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Conversation -->
    <div class="col-md-8">
        
        <!-- Original Ticket -->
        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex align-items-center">
                <div class="avatar bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-weight: bold; font-size: 1.2rem;">
                    {{ substr($ticket->creator->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h5 class="mb-1 fw-bold text-main">{{ $ticket->subject }}</h5>
                    <div class="small text-muted">
                        Opened by <strong>{{ $ticket->creator->name ?? 'System' }}</strong> &bull; {{ $ticket->created_at->format('M d, Y h:i A') }}
                    </div>
                </div>
            </div>
            <div class="card-body p-4 text-main" style="line-height: 1.6;">
                {!! nl2br(e($ticket->description)) !!}
            </div>
        </div>

        <!-- Replies List -->
        <div id="replies-container">
            @foreach($ticket->replies as $reply)
                <div class="card shadow-sm border-0 mb-4 {{ $reply->is_internal ? 'border-start border-4 border-warning' : '' }}" style="background: {{ $reply->is_internal ? 'rgba(255,193,7,0.05)' : 'var(--card-bg)' }};">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm bg-{{ $reply->is_internal ? 'warning' : 'secondary' }} bg-opacity-10 text-{{ $reply->is_internal ? 'warning' : 'secondary' }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; font-weight: bold; font-size: 0.85rem;">
                                    {{ substr($reply->user->name ?? 'S', 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold">{{ $reply->user->name ?? 'System' }}</h6>
                                    <div class="small text-muted">{{ $reply->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            @if($reply->is_internal)
                                <span class="badge bg-warning text-dark align-self-start"><i class="bi bi-lock-fill me-1"></i>Internal Note</span>
                            @endif
                        </div>
                        <div class="text-main" style="line-height: 1.6;">
                            {!! nl2br(e($reply->message)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Reply Form -->
        @include('tickets.partials.reply-form')

    </div>

    <!-- Right Column: Details Sidebar -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4">
                <h6 class="text-uppercase text-muted fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Ticket Details</h6>
                
                <div class="mb-4">
                    <label class="text-muted small mb-1">Ticket ID</label>
                    <div class="fw-bold fs-6">{{ $ticket->ticket_number }}</div>
                </div>

                <div class="mb-4">
                    <label class="text-muted small mb-1">Client</label>
                    @if($ticket->client)
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                {{ substr($ticket->client->company_name, 0, 1) }}
                            </div>
                            <a href="{{ route('clients.show', $ticket->client->id) }}" class="fw-medium text-decoration-none">{{ $ticket->client->company_name }}</a>
                        </div>
                    @else
                        <div class="fw-medium text-muted">Internal Ticket</div>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="text-muted small mb-1">Category</label>
                    <div class="fw-medium">{{ $ticket->category }}</div>
                </div>

                <div class="mb-4">
                    <label class="text-muted small mb-1">Assigned To</label>
                    @if($ticket->assignee)
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-check text-success me-2"></i>
                            <span class="fw-medium">{{ $ticket->assignee->name }}</span>
                        </div>
                    @else
                        <div class="text-muted fst-italic">Unassigned</div>
                    @endif
                </div>

                <div>
                    <label class="text-muted small mb-1">Created</label>
                    <div class="fw-medium">{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
