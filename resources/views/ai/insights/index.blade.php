@extends('layouts.master')

@section('title', 'AI Business Insights')

@push('custom-css')
<style>
    .ai-gradient-text {
        background: linear-gradient(135deg, #3b82f6, #10b981);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .ai-gradient-bg {
        background: linear-gradient(135deg, #3b82f6, #10b981);
    }
    .glass-panel {
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .kpi-card {
        background: var(--primary-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.2s;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
    }
    .markdown-output {
        font-family: system-ui, -apple-system, sans-serif;
        font-size: 1.05rem;
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
                <i class="bi bi-graph-up-arrow"></i>
            </div>
            AI Business Insights
        </h1>
        <p class="text-muted mb-0 mt-2">Generate strategic recommendations based on your CRM data.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Top section: KPIs -->
    <div class="col-12">
        <div class="glass-panel p-4">
            <h5 class="fw-bold mb-4">Current Business Snapshot</h5>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.5rem;">
                            <i class="bi bi-briefcase"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $kpi['active_projects'] }}</h3>
                            <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Active Projects</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.5rem;">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $kpi['overdue_tasks'] }}</h3>
                            <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Overdue Tasks</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.5rem;">
                            <i class="bi bi-ticket-detailed"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $kpi['open_tickets'] }}</h3>
                            <div class="text-muted small text-uppercase fw-bold letter-spacing-1">Open Tickets</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="kpi-card">
                        <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.5rem;">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h3 class="fw-bold mb-0">{{ $kpi['revenue_ytd'] }}</h3>
                            <div class="text-muted small text-uppercase fw-bold letter-spacing-1">YTD Revenue</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5 mb-2">
                <form id="generateInsightsForm">
                    @csrf
                    <!-- Pass KPI data invisibly to the backend -->
                    @foreach($kpi as $key => $val)
                        <input type="hidden" name="kpi_data[{{ $key }}]" value="{{ $val }}">
                    @endforeach
                    <button type="submit" class="btn text-white py-3 px-5 fw-bold shadow d-inline-flex align-items-center justify-content-center ai-gradient-bg border-0 rounded-pill fs-5" id="generateBtn">
                        <i class="bi bi-stars me-2"></i> Generate AI Strategy Report
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Output Pane -->
    <div class="col-12">
        <div class="glass-panel p-0">
            <div class="p-3 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h6 class="mb-0 fw-bold"><i class="bi bi-robot text-primary me-2"></i>AI Recommendations</h6>
            </div>
            
            <div class="p-5 position-relative" style="min-height: 400px;">
                <!-- Empty State -->
                <div id="emptyState" class="text-center position-absolute top-50 start-50 translate-middle w-100">
                    <i class="bi bi-bar-chart-line text-muted opacity-25" style="font-size: 5rem;"></i>
                    <h5 class="text-muted mt-3">Ready for Analysis</h5>
                    <p class="text-muted small">Click the generate button above to analyze your CRM performance.</p>
                </div>

                <!-- Loading State -->
                <div id="loadingState" class="text-center position-absolute top-50 start-50 translate-middle w-100 d-none">
                    <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;"></div>
                    <h5 class="ai-gradient-text fw-bold">Analyzing your business metrics...</h5>
                </div>

                <!-- Output Area -->
                <div id="insightsOutput" class="markdown-output d-none h-100 w-100"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $('#generateInsightsForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $('#generateBtn');
            const originalBtnHtml = $btn.html();
            
            // UI State updates
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Generating...');
            $('#emptyState').addClass('d-none');
            $('#insightsOutput').addClass('d-none').html('');
            $('#loadingState').removeClass('d-none');
            
            $.ajax({
                url: "{{ route('ai.insights.generate') }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#loadingState').addClass('d-none');
                        
                        // Parse markdown
                        const htmlContent = marked.parse(response.insights);
                        
                        $('#insightsOutput').html(htmlContent).removeClass('d-none');
                        
                        showToast('Success', 'Insights generated successfully!', 'success');
                    } else {
                        throw new Error(response.message);
                    }
                },
                error: function(xhr) {
                    $('#loadingState').addClass('d-none');
                    $('#emptyState').removeClass('d-none').find('h5').text('Generation Failed').addClass('text-danger');
                    showToast('Error', xhr.responseJSON?.message || 'Failed to generate insights.', 'error');
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalBtnHtml);
                }
            });
        });
    });
</script>
@endpush
