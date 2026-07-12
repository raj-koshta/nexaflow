@extends('layouts.master')

@section('title', 'Dashboard')

@push('custom-css')
<style>
    .stat-card {
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .timeline-widget {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 10px;
    }
    
    /* Scrollbar for widgets */
    .timeline-widget::-webkit-scrollbar {
        width: 6px;
    }
    .timeline-widget::-webkit-scrollbar-track {
        background: transparent;
    }
    .timeline-widget::-webkit-scrollbar-thumb {
        background-color: var(--border-color);
        border-radius: 10px;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, {{ auth()->user()->name }}! Here's your overview.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <button type="button" class="btn btn-outline-primary shadow-sm" onclick="location.href='{{ route('activities.index') }}'">
            <i class="bi bi-activity me-1"></i> Log Activity
        </button>
        <button type="button" class="btn btn-primary shadow-sm" onclick="location.href='{{ route('tasks.index') }}'">
            <i class="bi bi-plus-lg me-1"></i> New Task
        </button>
    </div>
</div>

<!-- AI Recommendation Widget -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-primary bg-opacity-10" style="border: 1px solid rgba(var(--bs-primary-rgb), 0.2);">
            <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-3 p-4">
                <div class="d-flex align-items-center">
                    <div class="avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 48px; height: 48px;">
                        <i class="bi bi-robot fs-4"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-primary mb-1">NexaFlow AI Tip</h5>
                        <p class="mb-0 text-muted" id="aiTipText">You have {{ $metrics['tasks']['pending'] }} pending tasks and {{ $metrics['tickets']['open'] }} open tickets. Consider prioritizing critical support tickets today.</p>
                    </div>
                </div>
                <a href="{{ route('ai.insights.index') }}" class="btn btn-primary btn-sm px-4 rounded-pill">View Full Insights</a>
            </div>
        </div>
    </div>
</div>

<!-- Key Metrics Row 1 -->
<div class="row g-4 mb-4">
    <!-- Active Projects -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Active Projects</h6>
                        <h2 class="fw-bold mb-0 text-main">{{ $metrics['projects']['active'] }}</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-briefcase"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-sm">
                    <span class="text-muted small">
                        Out of <span class="fw-bold">{{ $metrics['projects']['total'] }}</span> total
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Tasks -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Pending Tasks</h6>
                        <h2 class="fw-bold mb-0 text-main">{{ $metrics['tasks']['pending'] }}</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-list-check"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-sm">
                    <span class="text-muted small">
                        <span class="text-success fw-bold">{{ $metrics['tasks']['completed'] }}</span> completed
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Open Tickets -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Open Tickets</h6>
                        <h2 class="fw-bold mb-0 {{ $metrics['tickets']['open'] > 0 ? 'text-danger' : 'text-main' }}">{{ $metrics['tickets']['open'] }}</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-ticket-detailed"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-sm">
                    <span class="badge bg-success bg-opacity-10 text-success me-2">
                        {{ $metrics['tickets']['resolved'] }} Resolved
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversion Rate -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Lead Conversion Rate</h6>
                        <h2 class="fw-bold mb-0 text-main">{{ $metrics['leads']['conversion_rate'] }}%</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-info bg-opacity-10 text-info">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                </div>
                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar bg-info" role="progressbar" style="width: {{ $metrics['leads']['conversion_rate'] }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Growth Chart -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4">
                <h5 class="fw-bold mb-0">Client & Lead Growth (Last 6 Months)</h5>
            </div>
            <div class="card-body p-4">
                <div class="chart-container">
                    <canvas id="growthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Project Status Doughnut -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4">
                <h5 class="fw-bold mb-0">Project Status</h5>
            </div>
            <div class="card-body p-4 d-flex justify-content-center align-items-center">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Widgets Row -->
<div class="row g-4">
    <!-- My Tasks -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-ui-checks me-2 text-primary"></i>My Tasks</h5>
                <a href="{{ route('tasks.index') }}" class="text-muted small text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @if(count($metrics['tasks']['my_tasks']) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($metrics['tasks']['my_tasks'] as $task)
                        <a href="{{ route('tasks.show', $task) }}" class="list-group-item list-group-item-action p-3 bg-transparent border-bottom" style="border-color: var(--border-color) !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-main fw-medium">{{ $task->title }}</h6>
                                    @if($task->project)
                                        <div class="small text-muted"><i class="bi bi-briefcase me-1"></i>{{ $task->project->title }}</div>
                                    @endif
                                </div>
                                @if($task->due_date)
                                    <span class="badge {{ \Carbon\Carbon::parse($task->due_date)->isPast() ? 'bg-danger' : 'bg-secondary' }} rounded-pill">
                                        {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                    </span>
                                @endif
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="bi bi-check-all fs-1 opacity-50 mb-3 d-block"></i>
                        <p class="mb-0">No tasks assigned to you!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Open Tickets -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-ticket-detailed me-2 text-danger"></i>Open Tickets</h5>
                <a href="{{ route('tickets.index', ['status' => 'Open']) }}" class="text-muted small text-decoration-none">View All</a>
            </div>
            <div class="card-body p-0">
                @if(count($metrics['tickets']['critical_list']) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($metrics['tickets']['critical_list'] as $ticket)
                        <a href="{{ route('tickets.show', $ticket) }}" class="list-group-item list-group-item-action p-3 bg-transparent border-bottom" style="border-color: var(--border-color) !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 text-main fw-medium text-truncate" style="max-width: 200px;">{{ $ticket->subject }}</h6>
                                    <div class="small text-muted">{{ $ticket->client->company_name ?? 'Unknown' }}</div>
                                </div>
                                @php
                                    $pColor = 'secondary';
                                    if($ticket->priority == 'High') $pColor = 'warning';
                                    if($ticket->priority == 'Urgent') $pColor = 'danger';
                                @endphp
                                <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} rounded-pill">
                                    {{ $ticket->priority }}
                                </span>
                            </div>
                        </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="bi bi-inbox fs-1 opacity-50 mb-3 d-block"></i>
                        <p class="mb-0">No open tickets. Great job!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Global Activity -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Recent Activity</h5>
                <a href="{{ route('activities.index') }}" class="text-muted small text-decoration-none">Timeline</a>
            </div>
            <div class="card-body p-4 timeline-widget" style="max-height: 400px;">
                @if(count($metrics['activities']['latest']) > 0)
                    <div class="position-relative ms-3">
                        <div class="position-absolute top-0 bottom-0 border-start" style="left: -1px; border-color: var(--border-color) !important; border-width: 2px !important;"></div>
                        
                        @foreach($metrics['activities']['latest'] as $activity)
                        @php
                            $icon = 'bi-activity';
                            $color = 'primary';
                            switch(strtolower($activity->type)) {
                                case 'phone call': $icon = 'bi-telephone-fill'; $color = 'info'; break;
                                case 'meeting': $icon = 'bi-people-fill'; $color = 'success'; break;
                                case 'email': $icon = 'bi-envelope-fill'; $color = 'warning'; break;
                                case 'demo': $icon = 'bi-display'; $color = 'danger'; break;
                                case 'follow-up': $icon = 'bi-arrow-repeat'; $color = 'primary'; break;
                            }
                        @endphp
                        
                        <div class="position-relative mb-4 ps-4">
                            <div class="position-absolute bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                 style="left: -16px; width: 32px; height: 32px; top: 0; z-index: 2;">
                                <i class="bi {{ $icon }} small"></i>
                            </div>
                            
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size: 0.9rem;">{{ $activity->title }}</h6>
                                <span class="text-muted" style="font-size: 0.75rem;" title="{{ $activity->activity_date->format('Y-m-d H:i') }}">
                                    {{ $activity->activity_date->diffForHumans() }} by {{ $activity->creator->name ?? 'System' }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <p class="mb-0">No recent activity.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // Get CSS Variables for consistent theming
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    const textColor = isDark ? '#adb5bd' : '#6c757d';
    const gridColor = isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)';
    
    Chart.defaults.color = textColor;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // Growth Chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    const growthChartData = @json($metrics['charts']['growth']);
    
    new Chart(growthCtx, {
        type: 'line',
        data: {
            labels: growthChartData.labels,
            datasets: [
                {
                    label: 'New Clients',
                    data: growthChartData.clients,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'New Leads',
                    data: growthChartData.leads,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    tension: 0.4,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor,
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                }
            }
        }
    });

    // Project Status Chart
    const projectCtx = document.getElementById('projectChart').getContext('2d');
    const projectChartData = @json($metrics['charts']['projects']);
    
    new Chart(projectCtx, {
        type: 'doughnut',
        data: {
            labels: projectChartData.labels,
            datasets: [{
                data: projectChartData.data,
                backgroundColor: [
                    '#6c757d', // Not Started
                    '#3b82f6', // In Progress
                    '#f59e0b', // On Hold
                    '#10b981'  // Completed
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20
                    }
                }
            }
        }
    });
});
</script>
@endpush
