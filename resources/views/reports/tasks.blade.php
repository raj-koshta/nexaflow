@extends('layouts.master')

@section('title', 'Task Report')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" class="text-decoration-none">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Task Report</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">Task Report</h1>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-muted text-uppercase small letter-spacing-1 mb-1">Total Tasks</h5>
                    <h2 class="fw-bold text-primary mb-0">{{ $totalTasks }}</h2>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-list-task fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-muted text-uppercase small letter-spacing-1 mb-1">Completed</h5>
                    <h2 class="fw-bold text-success mb-0">{{ $completedTasks }}</h2>
                </div>
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-check-circle-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-muted text-uppercase small letter-spacing-1 mb-1">Completion Rate</h5>
                    <h2 class="fw-bold text-info mb-0">{{ $completionRate }}%</h2>
                </div>
                <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-percent fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-5">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0">Tasks by Status</h6>
            </div>
            <div class="card-body">
                @if($tasksByStatus->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                            <tbody>
                                @foreach($tasksByStatus as $stat)
                                <tr>
                                    <td class="border-0">
                                        @php
                                            $sColor = match($stat->status) {
                                                'Todo' => 'secondary', 'In Progress' => 'primary', 'Review' => 'warning', 'Done' => 'success', default => 'secondary'
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

    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100 border-start border-danger border-4" style="background: var(--card-bg);">
            <div class="card-header bg-transparent border-bottom pt-4 pb-3" style="border-color: var(--border-color) !important;">
                <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i>Overdue Tasks</h6>
            </div>
            <div class="card-body p-0">
                @if($overdueTasks->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                            <thead style="background: rgba(255,255,255,0.02);">
                                <tr>
                                    <th class="border-bottom-0 text-uppercase text-muted ps-4" style="font-size: 0.75rem; letter-spacing: 1px;">Task Title</th>
                                    <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Project</th>
                                    <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Assignee</th>
                                    <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($overdueTasks as $task)
                                <tr style="border-bottom: 1px solid var(--border-color);">
                                    <td class="py-3 ps-4 fw-medium">{{ $task->title }}</td>
                                    <td>{{ Str::limit($task->project->name, 20) }}</td>
                                    <td>
                                        @if($task->assignee)
                                            <span class="small">{{ $task->assignee->name }}</span>
                                        @else
                                            <span class="text-muted small fst-italic">Unassigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-2">{{ $task->due_date->format('M d, Y') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-emoji-smile text-success fs-1 mb-2 d-block"></i>
                        <p class="text-muted mb-0">Great job! You have no overdue tasks.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
