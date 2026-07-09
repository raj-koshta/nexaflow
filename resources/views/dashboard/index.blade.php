@extends('layouts.master')

@section('title', 'Dashboard')

@push('custom-css')
<style>
    .stat-card {
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
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
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Dashboard</h1>
        <p class="text-muted mb-0">Welcome back! Here's what's happening today.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <button type="button" class="btn btn-outline-primary shadow-sm" onclick="location.href='{{ route('activities.index') }}'">
            <i class="bi bi-activity me-1"></i> Log Activity
        </button>
        <button type="button" class="btn btn-primary shadow-sm" onclick="location.href='{{ route('follow-ups.index') }}'">
            <i class="bi bi-plus-lg me-1"></i> Schedule Follow Up
        </button>
    </div>
</div>

<!-- Key Metrics Row -->
<div class="row g-4 mb-4">
    <!-- Total Clients -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Total Clients</h6>
                        <h2 class="fw-bold mb-0 text-main">{{ $metrics['clients']['total'] }}</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-building"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-sm">
                    <span class="badge bg-success bg-opacity-10 text-success me-2">
                        <i class="bi bi-arrow-up-right me-1"></i>{{ $metrics['clients']['new_this_month'] }}
                    </span>
                    <span class="text-muted small">New this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Clients -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Active Clients</h6>
                        <h2 class="fw-bold mb-0 text-main">{{ $metrics['clients']['active'] }}</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-sm">
                    <span class="text-muted small">
                        <span class="fw-medium text-main">{{ $metrics['clients']['inactive'] }}</span> inactive
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Leads -->
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h6 class="text-muted fw-semibold mb-1">Total Leads</h6>
                        <h2 class="fw-bold mb-0 text-main">{{ $metrics['leads']['total'] }}</h2>
                    </div>
                    <div class="stat-icon-wrapper bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-funnel"></i>
                    </div>
                </div>
                <div class="d-flex align-items-center text-sm">
                    <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                        {{ $metrics['leads']['converted'] }} Converted
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
                        <h6 class="text-muted fw-semibold mb-1">Conversion Rate</h6>
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

<div class="row g-4">
    <!-- Today's Agenda (Follow Ups & Meetings) -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h5 class="fw-bold mb-0"><i class="bi bi-calendar-event me-2 text-primary"></i>Today's Agenda</h5>
                <span class="badge bg-primary rounded-pill">{{ $metrics['follow_ups']['today_count'] }} Tasks</span>
            </div>
            <div class="card-body p-0">
                @if(count($metrics['follow_ups']['today_list']) > 0)
                    <div class="list-group list-group-flush">
                        @foreach($metrics['follow_ups']['today_list'] as $followUp)
                        <div class="list-group-item p-4 bg-transparent border-bottom" style="border-color: var(--border-color) !important;">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <div class="d-flex align-items-center mb-1">
                                        @if($followUp->follow_time)
                                            <span class="text-danger fw-bold me-3" style="font-size: 0.9rem;">
                                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($followUp->follow_time)->format('h:i A') }}
                                            </span>
                                        @endif
                                        
                                        @if($followUp->client)
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill">
                                                Client: {{ $followUp->client->company_name }}
                                            </span>
                                        @elseif($followUp->lead)
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill">
                                                Lead: {{ $followUp->lead->name }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mb-0 text-main fw-medium">{{ $followUp->remarks ?: 'No specific remarks provided.' }}</p>
                                </div>
                                <button class="btn btn-outline-success btn-sm rounded-pill px-3 mark-complete-btn" data-id="{{ $followUp->id }}">
                                    <i class="bi bi-check2 me-1"></i>Complete
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="bi bi-check2-circle fs-1 opacity-50 mb-3 d-block"></i>
                        <p class="mb-0">You're all caught up for today!</p>
                    </div>
                @endif
                
                @if($metrics['follow_ups']['today_count'] > 5)
                <div class="p-3 text-center border-top" style="border-color: var(--border-color) !important;">
                    <a href="{{ route('follow-ups.index', ['category' => 'today']) }}" class="text-decoration-none">View all {{ $metrics['follow_ups']['today_count'] }} tasks <i class="bi bi-arrow-right ms-1"></i></a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Meetings -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h5 class="fw-bold mb-0"><i class="bi bi-people me-2 text-success"></i>Upcoming Meetings</h5>
                <span class="badge bg-success rounded-pill">{{ $metrics['meetings']['upcoming_count'] }}</span>
            </div>
            <div class="card-body p-4">
                @if(count($metrics['meetings']['upcoming_list']) > 0)
                    <div class="timeline-widget pe-2">
                        @foreach($metrics['meetings']['upcoming_list'] as $meeting)
                        <div class="d-flex mb-4">
                            <div class="me-3 text-center" style="min-width: 50px;">
                                <div class="text-muted fw-bold" style="font-size: 0.8rem;">{{ $meeting->activity_date->format('M') }}</div>
                                <div class="text-main fw-bold fs-5 lh-1">{{ $meeting->activity_date->format('d') }}</div>
                            </div>
                            <div class="p-3 rounded w-100 border" style="background: var(--secondary-bg); border-color: var(--border-color) !important;">
                                <h6 class="fw-bold mb-1">{{ $meeting->title }}</h6>
                                <p class="text-muted small mb-0">
                                    <i class="bi bi-clock me-1"></i>{{ $meeting->activity_date->format('h:i A') }}
                                </p>
                                @if($meeting->client)
                                    <div class="small mt-2"><i class="bi bi-building me-1 text-muted"></i>{{ $meeting->client->company_name }}</div>
                                @elseif($meeting->lead)
                                    <div class="small mt-2"><i class="bi bi-person me-1 text-muted"></i>{{ $meeting->lead->name }}</div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-calendar-x fs-1 opacity-50 mb-3 d-block"></i>
                        <p class="mb-0">No upcoming meetings scheduled.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Global Recent Activity -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-4 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-info"></i>Recent Global Activity</h5>
                <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-secondary">View Timeline</a>
            </div>
            <div class="card-body p-4 timeline-widget" style="max-height: 500px;">
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
                            <div class="position-absolute bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="left: -16px; width: 32px; height: 32px; top: 0; z-index: 2;">
                                <i class="bi {{ $icon }} small"></i>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="fw-bold mb-1">{{ $activity->title }}</h6>
                                    <div class="d-flex gap-2 flex-wrap mb-1 align-items-center">
                                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 rounded-pill px-2" style="font-size: 0.7rem;">
                                            {{ $activity->type }}
                                        </span>
                                        @if($activity->client)
                                            <span class="text-muted small"><i class="bi bi-building me-1"></i>{{ $activity->client->company_name }}</span>
                                        @elseif($activity->lead)
                                            <span class="text-muted small"><i class="bi bi-funnel me-1"></i>{{ $activity->lead->name }}</span>
                                        @endif
                                    </div>
                                </div>
                                <span class="text-muted small" title="{{ $activity->activity_date->format('Y-m-d H:i') }}">
                                    {{ $activity->activity_date->diffForHumans() }}
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
    // Mark Follow Up as Completed from Dashboard
    $(document).on('click', '.mark-complete-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $btn = $(this);
        const originalContent = $btn.html();
        
        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
        
        $.ajax({
            url: `/follow-ups/${id}/complete`,
            type: 'POST',
            success: function(response) {
                showToast('Success', response.message, 'success');
                // Remove the item from the list with a nice animation
                $btn.closest('.list-group-item').slideUp(300, function() {
                    $(this).remove();
                    // Optionally, reload the page to refresh metrics if list is empty
                    if($('.list-group-item').length === 0) {
                        location.reload();
                    }
                });
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error completing follow up', 'error');
                $btn.html(originalContent).prop('disabled', false);
            }
        });
    });
</script>
@endpush
