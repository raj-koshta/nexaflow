@extends('layouts.master')

@section('title', 'AI Email Generator')

@push('custom-css')
<style>
    .ai-gradient-text {
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .ai-gradient-bg {
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
    }
    .glass-panel {
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-main);
    }
    .form-control:focus, .form-select:focus {
        border-color: #8b5cf6;
        box-shadow: 0 0 0 0.25rem rgba(139, 92, 246, 0.25);
    }
    .email-output {
        min-height: 400px;
        white-space: pre-wrap;
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 0.95rem;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom" style="border-color: var(--border-color) !important;">
    <div>
        <h1 class="h2 fw-bold mb-0 d-flex align-items-center">
            <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3 ai-gradient-bg" style="width: 40px; height: 40px;">
                <i class="bi bi-envelope-paper"></i>
            </div>
            AI Email Generator
        </h1>
        <p class="text-muted mb-0 mt-2">Generate professional, perfectly-worded emails in seconds.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Pane: Inputs -->
    <div class="col-lg-5">
        <div class="glass-panel p-4 h-100">
            <h5 class="fw-bold mb-4 ai-gradient-text"><i class="bi bi-magic me-2"></i>Email Details</h5>
            
            <form id="emailGeneratorForm">
                @csrf
                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Recipient Name <span class="text-secondary fw-normal">(Optional)</span></label>
                    <input type="text" class="form-control" name="recipient" placeholder="e.g. John Doe">
                </div>
                
                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Subject / Topic <span class="text-secondary fw-normal">(Optional)</span></label>
                    <input type="text" class="form-control" name="subject" placeholder="e.g. Project Proposal Follow-up">
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Purpose / Context <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="purpose" rows="4" placeholder="What should this email say? e.g. Following up on the proposal sent last week, asking if they have any questions, and suggesting a quick call on Thursday." required></textarea>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Tone</label>
                        <select class="form-select" name="tone" required>
                            <option value="Professional">Professional</option>
                            <option value="Friendly">Friendly</option>
                            <option value="Persuasive">Persuasive</option>
                            <option value="Urgent">Urgent</option>
                            <option value="Apologetic">Apologetic</option>
                            <option value="Casual">Casual</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Length</label>
                        <select class="form-select" name="length" required>
                            <option value="Short">Short & Concise</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="Long">Detailed</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Language</label>
                        <select class="form-select" name="language" required>
                            <option value="English">English</option>
                            <option value="Spanish">Spanish</option>
                            <option value="French">French</option>
                            <option value="German">German</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn text-white w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center ai-gradient-bg border-0 rounded-pill" id="generateBtn">
                    <i class="bi bi-stars me-2 fs-5"></i> Generate Email
                </button>
            </form>
        </div>
    </div>

    <!-- Right Pane: Output -->
    <div class="col-lg-7">
        <div class="glass-panel p-0 h-100 d-flex flex-column">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i>Generated Email</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" id="copyBtn" disabled>
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                    <button class="btn btn-sm btn-primary" id="openSendModalBtn" disabled>
                        <i class="bi bi-send me-1"></i> Send Email
                    </button>
                </div>
            </div>
            
            <div class="p-4 flex-grow-1 position-relative">
                <!-- Empty State -->
                <div id="emptyState" class="text-center position-absolute top-50 start-50 translate-middle w-100">
                    <i class="bi bi-mailbox2 text-muted opacity-25" style="font-size: 5rem;"></i>
                    <h5 class="text-muted mt-3">Ready to compose</h5>
                    <p class="text-muted small">Fill out the details on the left and click Generate.</p>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center position-absolute top-50 start-50 translate-middle w-100 d-none">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <h5 class="ai-gradient-text fw-bold">Writing your email...</h5>
                </div>

                <!-- Output Editor -->
                <textarea class="form-control border-0 email-output p-3 d-none w-100 h-100 focus-ring" id="emailOutput" style="resize: none; outline: none; box-shadow: none; background: var(--primary-bg); border-radius: 12px; position: relative; z-index: 10;"></textarea>
            </div>
        </div>
    </div>
</div>

<!-- Send Email Modal -->
<div class="modal fade" id="sendEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden; background: var(--card-bg);">
            <div class="modal-header border-bottom px-4 py-3" style="border-color: var(--border-color) !important;">
                <h5 class="modal-title fw-bold">Send Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-btn-filter);"></button>
            </div>
            <div class="modal-body p-4">
                <form id="sendEmailForm">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">To Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email_to" id="sendEmailTo" required placeholder="recipient@example.com">
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Subject (Optional)</label>
                        <input type="text" class="form-control" name="email_subject" id="sendEmailSubject" placeholder="Email subject line">
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" id="confirmSendBtn">
                            <i class="bi bi-send me-2"></i>Send Now
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $('#emailGeneratorForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $('#generateBtn');
            const originalBtnHtml = $btn.html();
            
            // UI State updates
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generating...');
            $('#emptyState').addClass('d-none');
            $('#emailOutput').addClass('d-none');
            $('#loadingState').removeClass('d-none');
            $('#copyBtn, #openSendModalBtn').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('ai.email.generate') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#loadingState').addClass('d-none');
                        $('#emailOutput').val(response.email).removeClass('d-none');
                        $('#copyBtn, #openSendModalBtn').prop('disabled', false);
                        showToast('Success', 'Email generated successfully!', 'success');
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#loadingState').addClass('d-none');
                    $('#emptyState').removeClass('d-none').find('h5').text('Generation Failed').addClass('text-danger');
                    showToast('Error', xhr.responseJSON?.message || 'Failed to generate email.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });

        // Copy functionality
        $('#copyBtn').off('click').on('click', function() {
            const emailText = $('#emailOutput').val();
            navigator.clipboard.writeText(emailText).then(function() {
                const $btn = $('#copyBtn');
                $btn.html('<i class="bi bi-check2"></i> Copied!').removeClass('btn-outline-secondary').addClass('btn-success');
                setTimeout(() => {
                    $btn.html('<i class="bi bi-clipboard me-1"></i> Copy').removeClass('btn-success').addClass('btn-outline-secondary');
                }, 2000);
            }).catch(function(err) {
                showToast('Error', 'Failed to copy to clipboard', 'error');
            });
        });

        // Open Send Modal
        $('#openSendModalBtn').off('click').on('click', function() {
            // Check if there is a generated subject to pre-fill
            const emailBody = $('#emailOutput').val();
            let subject = '';
            
            // Very simple extraction if the AI put "Subject: " at the top
            const subjectMatch = emailBody.match(/^Subject:\s*(.+)$/m);
            if (subjectMatch) {
                subject = subjectMatch[1];
            } else {
                // fallback to the form subject if they entered one
                subject = $('input[name="subject"]').val();
            }
            
            $('#sendEmailSubject').val(subject);
            $('#sendEmailTo').val(''); // Clear it
            
            const sendModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('sendEmailModal'));
            sendModal.show();
        });

        // Actual Send logic
        $('#sendEmailForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $('#confirmSendBtn');
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Sending...');
            
            // Get data
            const data = {
                _token: $('input[name="_token"]').val(),
                email_to: $('#sendEmailTo').val(),
                email_subject: $('#sendEmailSubject').val(),
                email_body: $('#emailOutput').val() // Grab whatever they edited in the textarea
            };
            
            $.ajax({
                url: "{{ route('ai.email.send') }}",
                type: 'POST',
                data: data,
                success: function(response) {
                    const sendModal = bootstrap.Modal.getInstance(document.getElementById('sendEmailModal'));
                    if (sendModal) {
                        sendModal.hide();
                    }
                    
                    showToast('Success', response.message, 'success');
                    
                    // Reset state
                    $('#emailGeneratorForm')[0].reset();
                    $('#sendEmailForm')[0].reset();
                    $('#emailOutput').addClass('d-none').val('');
                    $('#emptyState').removeClass('d-none').find('h5').text('Ready to compose').removeClass('text-danger');
                    $('#copyBtn, #openSendModalBtn').prop('disabled', true);
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Failed to send email.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
@endpush
