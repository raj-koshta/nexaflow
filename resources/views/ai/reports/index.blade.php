@extends('layouts.master')

@section('title', 'AI Report Generator')

@push('custom-css')
<style>
    .ai-gradient-text {
        background: linear-gradient(135deg, #10b981, #0ea5e9);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .ai-gradient-bg {
        background: linear-gradient(135deg, #10b981, #0ea5e9);
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
        border-color: #10b981;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
    }
    .markdown-output {
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 0.95rem;
        line-height: 1.7;
        color: var(--text-main);
    }
    .markdown-output h3 {
        font-size: 1.5rem;
        font-weight: 800;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        color: var(--text-main);
    }
    .markdown-output h4 {
        font-size: 1.15rem;
        font-weight: 700;
        margin-top: 1.25rem;
        margin-bottom: 0.75rem;
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
        font-weight: 700;
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
                <i class="bi bi-file-earmark-bar-graph"></i>
            </div>
            AI Report Generator
        </h1>
        <p class="text-muted mb-0 mt-2">Generate professional Weekly and Monthly status reports instantly.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Left Pane: Inputs -->
    <div class="col-lg-4">
        <div class="glass-panel p-4 h-100">
            <h5 class="fw-bold mb-4 ai-gradient-text"><i class="bi bi-sliders me-2"></i>Report Settings</h5>
            
            <form id="reportForm">
                @csrf
                <div class="mb-4">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Report Type <span class="text-danger">*</span></label>
                    <select class="form-select" name="report_type" required>
                        <option value="Weekly">Weekly Status Report</option>
                        <option value="Monthly">Monthly Executive Report</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Focus Area <span class="text-danger">*</span></label>
                    <select class="form-select" name="focus_area" id="focusAreaSelect" required>
                        <option value="All Projects">All Active Projects</option>
                        <option value="Specific Project">Specific Project</option>
                        <option value="Support & Tickets">Support & Tickets</option>
                        <option value="General Team Velocity">General Team Velocity</option>
                    </select>
                </div>
                
                <div class="mb-4 d-none" id="projectSelectContainer">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Select Project <span class="text-danger">*</span></label>
                    <select class="form-select" name="project_id" id="project_id">
                        <option value="">Select a Project...</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-5">
                    <label class="form-label small text-muted text-uppercase fw-bold letter-spacing-1">Custom Instructions <span class="text-secondary fw-normal">(Optional)</span></label>
                    <textarea class="form-control" name="custom_instructions" rows="4" placeholder="e.g. Focus specifically on the delays caused by the QA environment downtime..."></textarea>
                </div>

                <button type="submit" class="btn text-white w-100 py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center ai-gradient-bg border-0 rounded-pill" id="generateBtn">
                    <i class="bi bi-stars me-2 fs-5"></i> Generate Report
                </button>
            </form>
        </div>
    </div>

    <!-- Right Pane: Output -->
    <div class="col-lg-8">
        <div class="glass-panel p-0 h-100 d-flex flex-column">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h6 class="mb-0 fw-bold"><i class="bi bi-file-earmark-text text-primary me-2"></i>Generated Report</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-secondary" id="copyBtn" disabled>
                        <i class="bi bi-clipboard me-1"></i> Copy
                    </button>
                    <button class="btn btn-sm btn-outline-primary" id="downloadPdfBtn" disabled>
                        <i class="bi bi-download me-1"></i> Export PDF
                    </button>
                </div>
            </div>
            
            <div class="p-4 flex-grow-1 position-relative" style="min-height: 600px; overflow-y: auto;">
                <!-- Empty State -->
                <div id="emptyState" class="text-center position-absolute top-50 start-50 translate-middle w-100">
                    <i class="bi bi-file-earmark-bar-graph text-muted opacity-25" style="font-size: 5rem;"></i>
                    <h5 class="text-muted mt-3">Ready to generate</h5>
                    <p class="text-muted small">Configure your report settings and click Generate.</p>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center position-absolute top-50 start-50 translate-middle w-100 d-none">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <h5 class="ai-gradient-text fw-bold">Gathering data & writing report...</h5>
                </div>

                <!-- Output Area -->
                <div id="reportOutput" class="markdown-output d-none h-100 w-100"></div>
                <textarea id="hiddenRawReport" class="d-none"></textarea>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        // Handle Project Select Visibility
        $('#focusAreaSelect').on('change', function() {
            if ($(this).val() === 'Specific Project') {
                $('#projectSelectContainer').removeClass('d-none');
                $('#project_id').prop('required', true);
            } else {
                $('#projectSelectContainer').addClass('d-none');
                $('#project_id').prop('required', false).val('');
            }
        });

        // Form Submit
        $('#reportForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $('#generateBtn');
            const originalBtnHtml = $btn.html();
            
            // UI State updates
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generating...');
            $('#emptyState').addClass('d-none');
            $('#reportOutput').addClass('d-none').html('');
            $('#hiddenRawReport').val('');
            $('#loadingState').removeClass('d-none');
            $('#copyBtn, #downloadPdfBtn').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('ai.reports.generate') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#loadingState').addClass('d-none');
                        
                        // Parse markdown
                        const htmlContent = marked.parse(response.report);
                        
                        $('#hiddenRawReport').val(response.report);
                        $('#reportOutput').html(htmlContent).removeClass('d-none');
                        $('#copyBtn, #downloadPdfBtn').prop('disabled', false);
                        
                        showToast('Success', 'Report generated successfully!', 'success');
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#loadingState').addClass('d-none');
                    $('#emptyState').removeClass('d-none').find('h5').text('Generation Failed').addClass('text-danger');
                    showToast('Error', xhr.responseJSON?.message || 'Failed to generate report.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });

        // Copy functionality
        $('#copyBtn').off('click').on('click', function() {
            const reportText = $('#hiddenRawReport').val();
            navigator.clipboard.writeText(reportText).then(function() {
                const $btn = $('#copyBtn');
                $btn.html('<i class="bi bi-check2"></i> Copied!').removeClass('btn-outline-secondary').addClass('btn-success');
                setTimeout(() => {
                    $btn.html('<i class="bi bi-clipboard me-1"></i> Copy').removeClass('btn-success').addClass('btn-outline-secondary');
                }, 2000);
            }).catch(function(err) {
                showToast('Error', 'Failed to copy to clipboard', 'error');
            });
        });

        // Stubbed PDF Download
        $('#downloadPdfBtn').off('click').on('click', function() {
            showToast('Success', 'PDF Export functionality will be added in a future update!', 'info');
        });
    });
</script>
@endpush
