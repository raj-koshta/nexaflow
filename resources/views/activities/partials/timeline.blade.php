@if($activities->count() > 0)
    <div class="position-relative py-3 ps-3 ps-md-4">
        <!-- Timeline vertical line -->
        <div class="position-absolute top-0 bottom-0 border-start" style="left: 1.5rem; border-color: var(--border-color) !important; border-width: 2px !important; z-index: 1;"></div>
        
        @foreach($activities as $activity)
        @php
            $icon = 'bi-activity';
            $color = 'primary';
            
            switch(strtolower($activity->type)) {
                case 'phone call': $icon = 'bi-telephone-fill'; $color = 'info'; break;
                case 'meeting': $icon = 'bi-people-fill'; $color = 'success'; break;
                case 'email': $icon = 'bi-envelope-fill'; $color = 'warning'; break;
                case 'demo': $icon = 'bi-display'; $color = 'danger'; break;
                case 'visit': $icon = 'bi-geo-alt-fill'; $color = 'secondary'; break;
                case 'follow-up': $icon = 'bi-arrow-repeat'; $color = 'primary'; break;
            }
        @endphp
        
        <div class="position-relative mb-4 pb-2">
            <!-- Timeline Icon -->
            <div class="position-absolute bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                 style="left: -1rem; width: 36px; height: 36px; top: 0; border: 3px solid var(--primary-bg); z-index: 2;">
                <i class="bi {{ $icon }} fs-6"></i>
            </div>
            
            <div class="ms-4 ps-3">
                <div class="card shadow-sm border-0 position-relative" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap">
                            <h5 class="fw-bold mb-1">{{ $activity->title }}</h5>
                            <div class="d-flex align-items-center">
                                <small class="text-muted fw-medium me-3" title="{{ $activity->activity_date->format('Y-m-d H:i') }}">
                                    <i class="bi bi-clock me-1"></i>{{ $activity->activity_date->diffForHumans() }}
                                </small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="background: var(--secondary-bg); border: var(--glass-border);">
                                        <li><a class="dropdown-item edit-activity-btn" href="#" data-activity="{{ json_encode($activity) }}" style="color: var(--text-main);"><i class="bi bi-pencil me-2 text-muted"></i>Edit</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger delete-activity-btn" href="#" data-id="{{ $activity->id }}"><i class="bi bi-trash me-2"></i>Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        @if($activity->description)
                            <p class="text-muted mb-3" style="white-space: pre-line;">{{ $activity->description }}</p>
                        @endif
                        
                        <div class="d-flex gap-2 flex-wrap align-items-center">
                            <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 rounded-pill px-3">
                                {{ $activity->type }}
                            </span>
                            
                            @if($activity->client)
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">
                                <i class="bi bi-building me-1"></i>Client: {{ $activity->client->company_name }}
                            </span>
                            @elseif($activity->lead)
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-3">
                                <i class="bi bi-funnel me-1"></i>Lead: {{ $activity->lead->name }}
                            </span>
                            @endif
                            
                            <small class="text-muted ms-auto fst-italic">Logged by {{ $activity->creator->name }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-between align-items-center p-3 border-top mt-3" style="border-color: var(--border-color) !important;">
        <div class="text-muted small">
            Showing {{ $activities->firstItem() ?? 0 }} to {{ $activities->lastItem() ?? 0 }} of {{ $activities->total() }} entries
        </div>
        <div>
            {{ $activities->links('pagination::bootstrap-5') }}
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                <i class="bi bi-calendar2-x opacity-50"></i>
            </div>
            <h4 class="fw-bold">No activities found</h4>
            <p class="text-muted mb-4">Your timeline is empty. Start logging calls, meetings, or emails.</p>
            <button class="btn btn-primary px-4" onclick="openActivityOffcanvas()">
                <i class="bi bi-plus-lg me-2"></i>Log Activity
            </button>
        </div>
    </div>
@endif
