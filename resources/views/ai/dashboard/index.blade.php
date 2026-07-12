@extends('layouts.master')

@section('title', 'AI Analytics Dashboard')

@push('custom-css')
<style>
    .stat-card {
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    .ai-gradient-text {
        background: linear-gradient(135deg, #8b5cf6, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .table-container {
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 12px;
        overflow: hidden;
    }
    .prompt-cell {
        max-width: 300px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom" style="border-color: var(--border-color) !important;">
    <div>
        <h1 class="h2 fw-bold mb-0 d-flex align-items-center">
            <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background: linear-gradient(135deg, #8b5cf6, #3b82f6); width: 40px; height: 40px;">
                <i class="bi bi-speedometer2"></i>
            </div>
            AI Analytics Dashboard
        </h1>
        <p class="text-muted mb-0 mt-2">Monitor AI usage, performance, and activity logs across NexaFlow.</p>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-4 mb-4">
    <!-- Total Requests -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Total Requests</h6>
                <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-cpu fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-1 ai-gradient-text">{{ number_format($stats['total_requests']) }}</h2>
            <div class="small text-muted"><span class="text-success fw-bold">+{{ $stats['today_requests'] }}</span> requests today</div>
        </div>
    </div>
    
    <!-- Success Rate -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Success Rate</h6>
                <div class="avatar-sm bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-check-circle fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-1 text-main">{{ $stats['success_rate'] }}%</h2>
            <div class="small text-muted">Across all AI models</div>
        </div>
    </div>
    
    <!-- Avg Response Time -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Avg Response Time</h6>
                <div class="avatar-sm bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-stopwatch fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-1 text-main">{{ number_format($stats['avg_response_time']) }}<span class="fs-5 text-muted ms-1">ms</span></h2>
            <div class="small text-muted">For successful requests</div>
        </div>
    </div>
    
    <!-- Failed Requests -->
    <div class="col-md-6 col-xl-3">
        <div class="stat-card p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-muted text-uppercase fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Failed Requests</h6>
                <div class="avatar-sm bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                    <i class="bi bi-exclamation-triangle fs-5"></i>
                </div>
            </div>
            <h2 class="fw-bold mb-1 text-danger">{{ number_format($stats['failed_requests']) }}</h2>
            <div class="small text-muted">Total errors logged</div>
        </div>
    </div>
</div>

<!-- Activity Log Table -->
<div class="table-container shadow-sm">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
        <h5 class="fw-bold mb-0 text-main">Recent AI Activity Logs</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="color: var(--text-color);">
            <thead class="text-muted small text-uppercase" style="background: rgba(0,0,0,0.02); font-size: 0.75rem; letter-spacing: 1px;">
                <tr>
                    <th class="border-0 px-4 py-3">Timestamp</th>
                    <th class="border-0 py-3">User</th>
                    <th class="border-0 py-3">Feature</th>
                    <th class="border-0 py-3">Prompt Excerpt</th>
                    <th class="border-0 py-3 text-center">Time</th>
                    <th class="border-0 px-4 py-3 text-end">Status</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
                @forelse($logs as $log)
                <tr>
                    <td class="px-4 py-3 text-muted small">
                        {{ $log->created_at->format('M d, Y') }}<br>
                        <span style="font-size: 0.75rem;">{{ $log->created_at->format('h:i:s A') }}</span>
                    </td>
                    <td class="py-3 fw-medium">
                        {{ $log->user ? $log->user->name : 'System/Guest' }}
                    </td>
                    <td class="py-3">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $log->feature_name }}</span>
                    </td>
                    <td class="py-3 prompt-cell text-muted" title="{{ $log->prompt }}">
                        {{ $log->prompt }}
                    </td>
                    <td class="py-3 text-center text-muted small">
                        {{ $log->processing_time }}ms
                    </td>
                    <td class="px-4 py-3 text-end">
                        @if($log->is_successful)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">Success</span>
                        @else
                            <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 cursor-pointer" title="{{ $log->error_message }}">Failed</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-clock-history d-block mb-2 opacity-50" style="font-size: 2rem;"></i>
                        No AI activity logged yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 border-top d-flex justify-content-center" style="border-color: var(--border-color) !important;">
        {{ $logs->links() }}
    </div>
</div>
@endsection
