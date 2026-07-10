@extends('layouts.master')

@section('title', 'Reports Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Reports Dashboard</h1>
        <p class="text-muted">Analyze your business metrics and get actionable insights.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-buildings-fill text-primary fs-1 mb-2"></i>
                <h2 class="fw-bold">{{ $stats['total_clients'] }}</h2>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Total Clients</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-briefcase-fill text-success fs-1 mb-2"></i>
                <h2 class="fw-bold">{{ $stats['total_projects'] }}</h2>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Total Projects</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-check2-square text-warning fs-1 mb-2"></i>
                <h2 class="fw-bold">{{ $stats['total_tasks'] }}</h2>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Total Tasks</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-check-circle-fill text-info fs-1 mb-2"></i>
                <h2 class="fw-bold">{{ $stats['completed_tasks'] }}</h2>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Tasks Completed</p>
            </div>
        </div>
    </div>
</div>

<h4 class="fw-bold mb-4 mt-5">Available Reports</h4>
<div class="row g-4">
    <!-- Client Report -->
    <div class="col-md-4">
        <a href="{{ route('reports.clients') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="card-body p-4 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-people-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Client Report</h5>
                    <p class="text-muted small mb-0">Analyze client distribution across industries and statuses.</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Project Report -->
    <div class="col-md-4">
        <a href="{{ route('reports.projects') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="card-body p-4 text-center">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-bar-chart-fill fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Project Report</h5>
                    <p class="text-muted small mb-0">Track project budgets, progress, and overall portfolio health.</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Task Report -->
    <div class="col-md-4">
        <a href="{{ route('reports.tasks') }}" class="text-decoration-none">
            <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border); transition: transform 0.2s ease, box-shadow 0.2s ease;">
                <div class="card-body p-4 text-center">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                        <i class="bi bi-list-check fs-2"></i>
                    </div>
                    <h5 class="fw-bold text-main mb-2">Task Report</h5>
                    <p class="text-muted small mb-0">Review completion rates, overdue tasks, and workload.</p>
                </div>
            </div>
        </a>
    </div>
</div>

<style>
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection
