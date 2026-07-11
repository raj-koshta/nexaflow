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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h6 class="text-uppercase text-muted fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Ticket Details</h6>
                    <button class="btn btn-sm text-white px-3 py-1 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); border: none;" id="btn-ai-summarize">
                        <i class="bi bi-stars me-1"></i> Summarize with AI
                    </button>
                </div>
                
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
</div>

<!-- AI Reply Modal -->
<div class="modal fade" id="aiReplyModal" tabindex="-1" aria-labelledby="aiReplyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="background: var(--card-bg); border: var(--glass-border) !important; border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold d-flex align-items-center" id="aiReplyModalLabel">
                    <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); width: 36px; height: 36px;">
                        <i class="bi bi-stars"></i>
                    </div>
                    Generate AI Reply
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted small mb-3">Tell the AI Assistant what kind of response you want to draft. It will read the ticket history to create a professional email.</p>
                
                <div class="mb-3">
                    <label for="ai_intent" class="form-label fw-bold small">Your Intent / Instructions</label>
                    <textarea class="form-control" id="ai_intent" rows="3" placeholder="e.g. Apologize for the delay and tell them the refund has been processed." style="border-radius: 8px;"></textarea>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn text-white px-4 shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); border: none;" id="btn-generate-reply">
                        <i class="bi bi-magic me-1"></i> Generate Draft
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
$(document).ready(function() {
    // AI Summarize
    $('#btn-ai-summarize').off('click').on('click', function(e) {
        e.preventDefault();
        const $btn = $(this);
        const originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Thinking...');
        
        $.ajax({
            url: "{{ route('tickets.ai-summarize', $ticket->id) }}",
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                showToast('Success', 'AI has generated a summary internal note.', 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                showToast('Error', xhr.responseJSON?.message || 'Failed to generate summary.', 'error');
            }
        });
    });

    // AI Generate Reply
    $('#btn-generate-reply').off('click').on('click', function(e) {
        e.preventDefault();
        const intent = $('#ai_intent').val().trim();
        
        if (!intent) {
            showToast('Error', 'Please enter your intent first.', 'error');
            return;
        }

        const $btn = $(this);
        const originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Generating...');
        
        $.ajax({
            url: "{{ route('tickets.ai-reply', $ticket->id) }}",
            type: 'POST',
            data: { 
                _token: '{{ csrf_token() }}',
                intent: intent
            },
            success: function(res) {
                $btn.prop('disabled', false).html(originalHtml);
                
                // Set text in Summernote or Textarea
                if ($('#reply-message').hasClass('summernote')) {
                    $('#reply-message').summernote('code', res.draft);
                } else {
                    $('#reply-message').val(res.draft);
                }
                
                $('#aiReplyModal').modal('hide');
                $('#ai_intent').val('');
                showToast('Success', 'AI draft applied! You can review and edit it before sending.', 'success');
                
                // Scroll to reply box
                $('html, body').animate({
                    scrollTop: $("#reply-message").offset().top - 100
                }, 500);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                showToast('Error', xhr.responseJSON?.message || 'Failed to generate draft.', 'error');
            }
        });
    });
});
</script>
@endpush
