@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0" style="color: var(--text-main);">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back, here's what's happening with your business today.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="bi bi-share me-1"></i>Share</button>
            <button type="button" class="btn btn-sm" style="border: 1px solid var(--border-color); color: var(--text-main);"><i class="bi bi-download me-1"></i>Export</button>
        </div>
        <button type="button" class="btn btn-sm btn-primary text-white">
            <i class="bi bi-calendar3 me-1"></i> This week
        </button>
    </div>
</div>

<div class="row mb-4">
    <!-- Stat Card 1 -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.15) 0%, var(--card-bg) 100%);">
            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                <i class="bi bi-people-fill" style="font-size: 4rem; color: #8b5cf6;"></i>
            </div>
            <div class="card-body position-relative z-1">
                <h6 class="text-uppercase text-muted fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Total Clients</h6>
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: var(--text-main);">0</h2>
            </div>
        </div>
    </div>
    
    <!-- Stat Card 2 -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(56, 189, 248, 0.15) 0%, var(--card-bg) 100%);">
            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                <i class="bi bi-briefcase-fill" style="font-size: 4rem; color: #38bdf8;"></i>
            </div>
            <div class="card-body position-relative z-1">
                <h6 class="text-uppercase text-muted fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Active Projects</h6>
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: var(--text-main);">0</h2>
            </div>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="col-md-3 mb-4 mb-md-0">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.15) 0%, var(--card-bg) 100%);">
            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                <i class="bi bi-list-task" style="font-size: 4rem; color: #f59e0b;"></i>
            </div>
            <div class="card-body position-relative z-1">
                <h6 class="text-uppercase text-muted fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Pending Tasks</h6>
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: var(--text-main);">0</h2>
            </div>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="col-md-3">
        <div class="card h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, var(--card-bg) 100%);">
            <div class="position-absolute top-0 end-0 p-3 opacity-25">
                <i class="bi bi-life-preserver" style="font-size: 4rem; color: #ef4444;"></i>
            </div>
            <div class="card-body position-relative z-1">
                <h6 class="text-uppercase text-muted fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Open Tickets</h6>
                <h2 class="fw-bold mb-1" style="font-size: 2.5rem; color: var(--text-main);">0</h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4 text-center py-5">
                <div class="mb-4 text-primary" style="font-size: 4rem;">
                    <i class="bi bi-rocket-takeoff" style="color: var(--accent);"></i>
                </div>
                <h3 class="fw-bold mb-3">You're all set up!</h3>
                <p class="text-muted mx-auto" style="max-width: 600px;">
                    This is the placeholder dashboard for Phase 1 of NexaFlow. In upcoming phases, we will populate this area with live data charts, recent activities, and AI insights.
                </p>
                <button class="btn btn-primary mt-3 px-4">
                    Explore CRM Module <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
