@extends('layouts.master')

@section('title', 'Project Report')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" class="text-decoration-none">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Project Report</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">Project Report</h1>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="dropdown">
            <button class="btn btn-primary shadow-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-download me-1"></i> Export & Actions
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="background: var(--secondary-bg); border: var(--glass-border) !important;">
                <li><a class="dropdown-item py-2 text-main" href="{{ route('reports.projects.export') }}"><i class="bi bi-filetype-csv me-2 text-success"></i> Download CSV</a></li>
                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                <li><button class="dropdown-item py-2 text-main" onclick="window.print()"><i class="bi bi-printer me-2 text-muted"></i> Print Report</button></li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-muted text-uppercase small letter-spacing-1 mb-1">Total Projects</h5>
                    <h2 class="fw-bold text-success mb-0">{{ $totalProjects }}</h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-briefcase-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-muted text-uppercase small letter-spacing-1 mb-1">Total Budget</h5>
                    <h2 class="fw-bold text-success mb-0">${{ number_format($totalBudget, 2) }}</h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-cash-stack fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0">Projects by Status</h6>
            </div>
            <div class="card-body">
                @if($projectsByStatus->count() > 0)
                    <div class="mb-4" style="height: 250px;">
                        <canvas id="projectStatusChart"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                            <tbody>
                                @foreach($projectsByStatus as $stat)
                                <tr>
                                    <td class="border-0">
                                        @php
                                            $sColor = match($stat->status) {
                                                'Planning' => 'info', 'Active' => 'primary', 'On Hold' => 'warning',
                                                'Completed' => 'success', 'Cancelled' => 'danger', default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} rounded-pill px-2">{{ $stat->status }}</span>
                                    </td>
                                    <td class="border-0 text-end fw-bold">{{ $stat->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center my-4">No status data available.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0">Projects by Priority</h6>
            </div>
            <div class="card-body">
                @if($projectsByPriority->count() > 0)
                    <div class="mb-4" style="height: 250px;">
                        <canvas id="projectPriorityChart"></canvas>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                            <tbody>
                                @foreach($projectsByPriority as $stat)
                                <tr>
                                    <td class="border-0">
                                        @php
                                            $pColor = match($stat->priority) {
                                                'Critical' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} rounded-pill px-2">{{ $stat->priority }}</span>
                                    </td>
                                    <td class="border-0 text-end fw-bold">{{ $stat->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center my-4">No priority data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-header bg-transparent border-bottom pt-4 pb-3" style="border-color: var(--border-color) !important;">
        <h6 class="fw-bold mb-0">Recent Projects</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="border-bottom-0 text-uppercase text-muted ps-4" style="font-size: 0.75rem; letter-spacing: 1px;">Project Name</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Client</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Budget</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentProjects as $project)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td class="py-3 ps-4 fw-medium"><a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none" style="color: var(--text-main);">{{ $project->name }}</a></td>
                        <td>{{ $project->client->company_name }}</td>
                        <td>
                            @php
                                $sColor = match($project->status) {
                                    'Planning' => 'info', 'Active' => 'primary', 'On Hold' => 'warning',
                                    'Completed' => 'success', 'Cancelled' => 'danger', default => 'secondary'
                                };
                            @endphp
                            <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} rounded-pill px-2">{{ $project->status }}</span>
                        </td>
                        <td>${{ number_format($project->budget, 2) }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2 small fw-bold">{{ $project->progress }}%</span>
                                <div class="progress w-100" style="height: 6px; background-color: var(--border-color);">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No projects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Project Status Chart
        const statusData = {!! json_encode($projectsByStatus) !!};
        if (statusData.length > 0 && document.getElementById('projectStatusChart')) {
            const ctx = document.getElementById('projectStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: statusData.map(d => d.status),
                    datasets: [{
                        data: statusData.map(d => d.total),
                        backgroundColor: statusData.map(d => {
                            if (d.status === 'Planning') return '#0ea5e9';
                            if (d.status === 'Active') return '#3b82f6';
                            if (d.status === 'On Hold') return '#f59e0b';
                            if (d.status === 'Completed') return '#10b981';
                            if (d.status === 'Cancelled') return '#ef4444';
                            return '#94a3b8';
                        }),
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { color: 'var(--text-main)' } }
                    }
                }
            });
        }

        // Project Priority Chart
        const priorityData = {!! json_encode($projectsByPriority) !!};
        if (priorityData.length > 0 && document.getElementById('projectPriorityChart')) {
            const ctx2 = document.getElementById('projectPriorityChart').getContext('2d');
            new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: priorityData.map(d => d.priority),
                    datasets: [{
                        label: 'Projects',
                        data: priorityData.map(d => d.total),
                        backgroundColor: priorityData.map(d => {
                            if (d.priority === 'Critical') return '#ef4444';
                            if (d.priority === 'High') return '#f59e0b';
                            if (d.priority === 'Medium') return '#0ea5e9';
                            if (d.priority === 'Low') return '#64748b';
                            return '#94a3b8';
                        }),
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, color: 'var(--text-main)' },
                            grid: { color: 'rgba(255, 255, 255, 0.05)' }
                        },
                        x: {
                            ticks: { color: 'var(--text-main)' },
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
