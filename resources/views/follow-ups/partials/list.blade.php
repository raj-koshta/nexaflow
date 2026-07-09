@if($followUps->count() > 0)
    <div class="row g-4">
        @foreach($followUps as $followUp)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 position-relative follow-up-card" style="background: var(--card-bg); border: var(--glass-border);">
                @if($followUp->status === 'Completed')
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-success bg-opacity-10 z-0" style="border-radius: inherit; pointer-events: none;"></div>
                @elseif($category === 'overdue')
                    <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger bg-opacity-10 z-0" style="border-radius: inherit; pointer-events: none;"></div>
                @endif
                
                <div class="card-body position-relative z-1">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex gap-2 flex-wrap">
                            @if($followUp->status === 'Completed')
                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Completed</span>
                            @else
                                <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>Pending</span>
                            @endif
                            
                            @if($followUp->client)
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                    <i class="bi bi-building me-1"></i>{{ Str::limit($followUp->client->company_name, 15) }}
                                </span>
                            @elseif($followUp->lead)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">
                                    <i class="bi bi-funnel me-1"></i>{{ Str::limit($followUp->lead->name, 15) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="background: var(--secondary-bg); border: var(--glass-border);">
                                <li><a class="dropdown-item edit-followup-btn" href="#" data-followup="{{ json_encode($followUp) }}" style="color: var(--text-main);"><i class="bi bi-pencil me-2 text-muted"></i>Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger delete-followup-btn" href="#" data-id="{{ $followUp->id }}"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        @if($followUp->remarks)
                            <p class="text-main mb-0 fw-medium" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                {{ $followUp->remarks }}
                            </p>
                        @else
                            <p class="text-muted fst-italic mb-0">No remarks provided.</p>
                        @endif
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px;">
                            {{ substr($followUp->assignee->name, 0, 1) }}
                        </div>
                        <div class="small">
                            <div class="text-muted" style="font-size: 0.75rem;">Assigned to</div>
                            <div class="fw-medium text-main">{{ $followUp->assignee->name }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center position-relative z-1" style="border-color: var(--border-color) !important;">
                    <div class="text-muted small">
                        <i class="bi bi-calendar-event me-1"></i>
                        @if($followUp->follow_date->isToday())
                            Today
                        @elseif($followUp->follow_date->isTomorrow())
                            Tomorrow
                        @elseif($followUp->follow_date->isYesterday())
                            Yesterday
                        @else
                            {{ $followUp->follow_date->format('M d, Y') }}
                        @endif
                        
                        @if($followUp->follow_time)
                            &bull; {{ \Carbon\Carbon::parse($followUp->follow_time)->format('h:i A') }}
                        @endif
                    </div>
                    
                    @if($followUp->status !== 'Completed')
                    <button class="btn btn-outline-success btn-sm mark-complete-btn" data-id="{{ $followUp->id }}">
                        <i class="bi bi-check2-all me-1"></i>Complete
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-between align-items-center p-3 border-top mt-4" style="border-color: var(--border-color) !important;">
        <div class="text-muted small">
            Showing {{ $followUps->firstItem() ?? 0 }} to {{ $followUps->lastItem() ?? 0 }} of {{ $followUps->total() }} entries
        </div>
        <div>
            {{ $followUps->links('pagination::bootstrap-5') }}
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                @if($category === 'completed')
                    <i class="bi bi-check2-circle opacity-50"></i>
                @elseif($category === 'overdue')
                    <i class="bi bi-clock-history opacity-50"></i>
                @else
                    <i class="bi bi-calendar2-x opacity-50"></i>
                @endif
            </div>
            <h4 class="fw-bold">
                @if($category === 'completed')
                    No completed follow ups
                @elseif($category === 'overdue')
                    No overdue follow ups!
                @elseif($category === 'today')
                    No follow ups scheduled for today
                @else
                    No upcoming follow ups
                @endif
            </h4>
            <p class="text-muted mb-4">
                @if($category === 'overdue')
                    You're all caught up! Great job staying on top of your tasks.
                @else
                    Keep your pipeline moving by scheduling your next interactions.
                @endif
            </p>
            <button class="btn btn-primary px-4" onclick="openFollowUpOffcanvas()">
                <i class="bi bi-plus-lg me-2"></i>Schedule Follow Up
            </button>
        </div>
    </div>
@endif
