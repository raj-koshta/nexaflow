@extends('layouts.master')

@section('title', 'AI Meeting Notes')

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
    .markdown-output {
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 0.95rem;
        line-height: 1.6;
    }
    .markdown-output h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }
    .markdown-output ul {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .markdown-output li {
        margin-bottom: 0.5rem;
    }
    .markdown-output strong {
        font-weight: 600;
        color: var(--text-main);
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom" style="border-color: var(--border-color) !important;">
    <div>
        <h1 class="h2 fw-bold mb-0 d-flex align-items-center">
            <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3 ai-gradient-bg" style="width: 40px; height: 40px;">
                <i class="bi bi-mic"></i>
            </div>
            AI Meeting Notes
        </h1>
        <p class="text-muted mb-0 mt-2">Instantly convert messy notes or transcripts into structured summaries and action items.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Pane: Inputs -->
    <div class="col-lg-5">
        <div class="glass-panel p-4 h-100">
            <h5 class="fw-bold mb-4 ai-gradient-text"><i class="bi bi-magic me-2"></i>Meeting Details</h5>
            
            <form id="meetingNotesForm">
                @csrf
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Meeting Title <span class="text-secondary fw-normal">(Optional)</span></label>
                        <input type="text" class="form-control" name="meeting_title" placeholder="e.g. Q3 Planning">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Date <span class="text-secondary fw-normal">(Optional)</span></label>
                        <input type="date" class="form-control" name="date">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Attendees <span class="text-secondary fw-normal">(Optional)</span></label>
                    <input type="text" class="form-control" name="attendees" placeholder="e.g. John, Jane, Mike">
                </div>

                <div class="mb-4">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Transcript / Raw Notes <span class="text-danger">*</span></label>
                    <textarea class="form-control" name="transcript" rows="12" placeholder="Paste your messy notes, Zoom transcript, or bullet points here..." required></textarea>
                </div>

                <button type="submit" class="btn text-white w-100 py-2 fw-bold shadow-sm d-flex align-items-center justify-content-center ai-gradient-bg border-0 rounded-pill" id="generateBtn">
                    <i class="bi bi-stars me-2 fs-5"></i> Generate Notes
                </button>
            </form>
        </div>
    </div>

    <!-- Right Pane: Output -->
    <div class="col-lg-7">
        <div class="glass-panel p-0 h-100 d-flex flex-column">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i>Structured Notes</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" id="copyBtn" disabled>
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                    <button class="btn btn-sm btn-primary" id="createTasksBtn" disabled>
                        <i class="bi bi-check2-square me-1"></i> Create Tasks
                    </button>
                </div>
            </div>
            
            <div class="p-4 flex-grow-1 position-relative" style="min-height: 500px; overflow-y: auto;">
                <!-- Empty State -->
                <div id="emptyState" class="text-center position-absolute top-50 start-50 translate-middle w-100">
                    <i class="bi bi-journal-text text-muted opacity-25" style="font-size: 5rem;"></i>
                    <h5 class="text-muted mt-3">Ready to summarize</h5>
                    <p class="text-muted small">Paste your transcript on the left and click Generate.</p>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center position-absolute top-50 start-50 translate-middle w-100 d-none">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <h5 class="ai-gradient-text fw-bold">Analyzing meeting...</h5>
                </div>

                <!-- Output Area -->
                <div id="notesOutput" class="markdown-output d-none h-100 w-100"></div>
                <textarea id="hiddenRawNotes" class="d-none"></textarea>
            </div>
        </div>
    </div>
</div>

<!-- Create Tasks Modal -->
<div class="modal fade" id="createTasksModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden; background-color: var(--secondary-bg, #ffffff);">
            <div class="modal-header border-bottom px-4 py-3" style="border-color: var(--border-color) !important;">
                <h5 class="modal-title fw-bold">Create Action Items as Tasks</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-btn-filter);"></button>
            </div>
            <div class="modal-body p-4">
                <form id="createTasksForm">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1">Assign to Project <span class="text-danger">*</span></label>
                        <select class="form-select" name="project_id" required>
                            <option value="">Select a Project...</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Detected Action Items</h6>
                    <div id="detectedTasksContainer" class="d-flex flex-column gap-2 mb-4">
                        <!-- Tasks will be injected here -->
                    </div>
                    
                    <div class="alert alert-info py-2 small mb-0">
                        <i class="bi bi-info-circle me-1"></i> You can edit the task titles above before creating them. Uncheck a task to exclude it.
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" id="confirmTasksBtn">
                            <i class="bi bi-check2-square me-2"></i>Create Tasks
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
        $('#meetingNotesForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $('#generateBtn');
            const originalBtnHtml = $btn.html();
            
            // UI State updates
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generating...');
            $('#emptyState').addClass('d-none');
            $('#notesOutput').addClass('d-none').html('');
            $('#hiddenRawNotes').val('');
            $('#loadingState').removeClass('d-none');
            $('#copyBtn, #createTasksBtn').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('ai.meetings.generate') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#loadingState').addClass('d-none');
                        
                        // Parse markdown
                        const htmlContent = marked.parse(response.notes);
                        
                        $('#hiddenRawNotes').val(response.notes);
                        $('#notesOutput').html(htmlContent).removeClass('d-none');
                        $('#copyBtn, #createTasksBtn').prop('disabled', false);
                        
                        showToast('Success', 'Notes generated successfully!', 'success');
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#loadingState').addClass('d-none');
                    $('#emptyState').removeClass('d-none').find('h5').text('Generation Failed').addClass('text-danger');
                    showToast('Error', xhr.responseJSON?.message || 'Failed to generate notes.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });

        // Copy functionality
        $('#copyBtn').off('click').on('click', function() {
            const notesText = $('#hiddenRawNotes').val();
            navigator.clipboard.writeText(notesText).then(function() {
                const $btn = $('#copyBtn');
                $btn.html('<i class="bi bi-check2"></i> Copied!').removeClass('btn-outline-secondary').addClass('btn-success');
                setTimeout(() => {
                    $btn.html('<i class="bi bi-clipboard me-1"></i> Copy').removeClass('btn-success').addClass('btn-outline-secondary');
                }, 2000);
            }).catch(function(err) {
                showToast('Error', 'Failed to copy to clipboard', 'error');
            });
        });

        // Parse Markdown and Create Tasks Modal
        $('#createTasksBtn').off('click').on('click', function() {
            const rawNotes = $('#hiddenRawNotes').val();
            const taskLines = [];
            
            // Basic regex to find markdown list items under Action Items
            // Looks for lines starting with - [ ] or * [ ]
            const lines = rawNotes.split('\n');
            let inActionItemsSection = false;
            
            for (let line of lines) {
                if (line.toLowerCase().includes('action item') || line.toLowerCase().includes('next steps')) {
                    inActionItemsSection = true;
                    continue;
                }
                if (line.startsWith('#') && inActionItemsSection) {
                    // We hit a new heading, exit section
                    inActionItemsSection = false;
                }
                
                if (inActionItemsSection) {
                    const match = line.match(/^[-*]\s*\[\s*[xX]?\s*\]\s*(.+)/);
                    if (match) {
                        taskLines.push(match[1]);
                    } else if (line.match(/^[-*]\s+(.+)/)) {
                        // Fallback to normal bullets if no checkbox
                        const bulletMatch = line.match(/^[-*]\s+(.+)/);
                        taskLines.push(bulletMatch[1]);
                    }
                }
            }
            
            const container = $('#detectedTasksContainer');
            container.empty();
            
            if (taskLines.length === 0) {
                container.html('<div class="text-muted small">No action items detected in the summary.</div>');
                $('#confirmTasksBtn').prop('disabled', true);
            } else {
                $('#confirmTasksBtn').prop('disabled', false);
                taskLines.forEach((task, index) => {
                    container.append(`
                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check mb-0">
                                <input class="form-check-input task-checkbox" type="checkbox" value="" id="taskCheck${index}" checked>
                            </div>
                            <input type="text" class="form-control form-control-sm task-input" value="${task.replace(/"/g, '&quot;')}">
                        </div>
                    `);
                });
            }
            
            const createModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('createTasksModal'));
            createModal.show();
        });

        // Actual Create Tasks logic
        $('#createTasksForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $('#confirmTasksBtn');
            const originalHtml = $btn.html();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status"></span> Creating...');
            
            const selectedTasks = [];
            $('#detectedTasksContainer .d-flex').each(function() {
                const isChecked = $(this).find('.task-checkbox').is(':checked');
                const val = $(this).find('.task-input').val();
                if (isChecked && val.trim() !== '') {
                    selectedTasks.push(val.trim());
                }
            });
            
            if (selectedTasks.length === 0) {
                showToast('Warning', 'No tasks selected.', 'warning');
                $btn.prop('disabled', false).html(originalHtml);
                return;
            }
            
            const data = {
                _token: $('input[name="_token"]').val(),
                project_id: $('select[name="project_id"]').val(),
                tasks: selectedTasks
            };
            
            $.ajax({
                url: "{{ route('ai.meetings.tasks') }}",
                type: 'POST',
                data: data,
                success: function(response) {
                    const createModal = bootstrap.Modal.getInstance(document.getElementById('createTasksModal'));
                    if (createModal) {
                        createModal.hide();
                    }
                    showToast('Success', response.message, 'success');
                    $('#createTasksForm')[0].reset();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Failed to create tasks.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalHtml);
                }
            });
        });
    });
</script>
@endpush
