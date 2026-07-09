<div class="card shadow-sm border-0 mt-4" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-body p-0">
        <!-- Reply Tabs -->
        <ul class="nav nav-tabs border-bottom-0 px-3 pt-3" id="replyTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-medium text-primary bg-transparent border-0 border-bottom border-primary border-2 px-4 py-2" id="public-reply-tab" data-bs-toggle="tab" data-bs-target="#public-reply" type="button" role="tab">
                    <i class="bi bi-reply-fill me-1"></i> Public Reply
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-medium text-muted bg-transparent border-0 px-4 py-2" id="internal-note-tab" data-bs-toggle="tab" data-bs-target="#internal-note" type="button" role="tab">
                    <i class="bi bi-lock-fill me-1"></i> Internal Note
                </button>
            </li>
        </ul>

        <!-- Reply Content -->
        <div class="tab-content" id="replyTabsContent">
            <!-- Public Reply Form -->
            <div class="tab-pane fade show active p-4" id="public-reply" role="tabpanel">
                <form class="reply-form" data-internal="0">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control bg-transparent text-main border-secondary border-opacity-25 focus-ring focus-ring-primary" name="message" rows="4" placeholder="Type your reply here... (Visible to client)" required style="resize: none;"></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small"><i class="bi bi-info-circle me-1"></i> This reply will be visible to the client.</div>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm submit-btn">
                            Send Reply <i class="bi bi-send ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Internal Note Form -->
            <div class="tab-pane fade p-4 rounded-bottom" id="internal-note" role="tabpanel" style="background: rgba(255,193,7,0.05);">
                <form class="reply-form" data-internal="1">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control bg-transparent text-main border-warning border-opacity-50 focus-ring focus-ring-warning" name="message" rows="4" placeholder="Type an internal note here... (Hidden from client)" required style="resize: none;"></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-warning small"><i class="bi bi-lock-fill me-1"></i> Only visible to staff members.</div>
                        <button type="submit" class="btn btn-warning px-4 shadow-sm submit-btn">
                            Add Note <i class="bi bi-journal-plus ms-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('custom-scripts')
<script>
$(document).ready(function() {
    // Tab styling logic
    $('#replyTabs button[data-bs-toggle="tab"]').off('shown.bs.tab').on('shown.bs.tab', function (e) {
        // Reset all tabs
        $('#replyTabs button').removeClass('text-primary text-warning border-bottom border-primary border-warning border-2');
        $('#replyTabs button').addClass('text-muted');
        
        // Style active tab
        const isInternal = $(e.target).attr('id') === 'internal-note-tab';
        $(e.target).removeClass('text-muted');
        
        if (isInternal) {
            $(e.target).addClass('text-warning border-bottom border-warning border-2');
        } else {
            $(e.target).addClass('text-primary border-bottom border-primary border-2');
        }
    });

    // Handle Reply Submission via AJAX
    $('.reply-form').off('submit').on('submit', function(e) {
        e.preventDefault();
        const $form = $(this);
        const $btn = $form.find('.submit-btn');
        const isInternal = $form.data('internal');
        const message = $form.find('textarea[name="message"]').val();
        
        if(!message.trim()) return;

        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...');

        $.ajax({
            url: '{{ route("tickets.replies.store", $ticket) }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                message: message,
                is_internal: isInternal ? 1 : 0
            },
            success: function(response) {
                if(response.success) {
                    showToast('Success', response.message, 'success');
                    $form.find('textarea').val(''); // Clear textarea
                    
                    setTimeout(() => window.location.reload(), 500);
                }
            },
            error: function(xhr) {
                showToast('Error', 'Failed to send reply.', 'error');
                const btnText = isInternal ? 'Add Note <i class="bi bi-journal-plus ms-2"></i>' : 'Send Reply <i class="bi bi-send ms-2"></i>';
                $btn.prop('disabled', false).html(btnText);
            }
        });
    });
});
</script>
@endpush
