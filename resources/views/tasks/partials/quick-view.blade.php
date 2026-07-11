<div class="row g-0">
    <div class="col-md-4 d-none d-md-block" style="background: var(--primary-bg); border-right: 1px solid var(--border-color);">
        <div class="p-4 h-100">
            <h6 class="text-uppercase text-muted fw-bold mb-4" style="font-size: 0.75rem; letter-spacing: 1px;">Task Details</h6>
            
            <div class="mb-3">
                <label class="text-muted small mb-1">Project</label>
                <div class="fw-bold fs-6 text-main">{{ $task->project->name }}</div>
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Milestone</label>
                <div class="fw-medium">
                    @if($task->milestone)
                        <span class="badge bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-flag me-1"></i>{{ $task->milestone->title }}</span>
                    @else
                        <span class="text-muted font-italic">No Milestone</span>
                    @endif
                </div>
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Priority</label>
                <div>
                    @php
                        $pColor = match($task->priority) {
                            'Urgent' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }}">{{ $task->priority }}</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Status</label>
                <div>
                    @php
                        $sColor = match($task->status) {
                            'Todo' => 'secondary', 'In Progress' => 'primary', 'Review' => 'warning', 'Done' => 'success', default => 'secondary'
                        };
                    @endphp
                    <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }}">{{ $task->status }}</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="text-muted small mb-1">Assigned To</label>
                <div class="d-flex align-items-center mt-1">
                    @if($task->assignee)
                        <div class="avatar-sm bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 24px; height: 24px; font-size: 0.75rem;">
                            {{ substr($task->assignee->name, 0, 1) }}
                        </div>
                        <div class="fw-medium">{{ $task->assignee->name }}</div>
                    @else
                        <div class="fw-medium text-muted font-italic">Unassigned</div>
                    @endif
                </div>
            </div>
            
            <div class="mb-3">
                <label class="text-muted small mb-1">Time Tracking</label>
                <div class="d-flex align-items-center small">
                    <i class="bi bi-clock me-2 text-muted"></i>
                    <span class="fw-medium">{{ $task->estimated_hours ?? 0 }} hrs</span> <span class="text-muted ms-1">estimated</span>
                </div>
            </div>
            
            @if($task->due_date)
            <div>
                <label class="text-muted small mb-1">Due Date</label>
                <div class="d-flex align-items-center small {{ $task->due_date < now() && $task->status != 'Done' ? 'text-danger fw-bold' : '' }}">
                    <i class="bi bi-calendar-event me-2"></i>
                    {{ $task->due_date->format('M d, Y') }}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="modal-header border-bottom px-4 py-3">
            <h5 class="modal-title fw-bold text-truncate" style="max-width: 90%;">{{ $task->title }}</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        
        <div class="modal-body p-0" style="height: 450px; overflow-y: auto; background: var(--card-bg);">
            <div class="p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom" style="border-color: var(--border-color) !important;">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px;">
                            {{ substr($task->creator->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div class="text-muted" style="font-size: 0.7rem;">Created by</div>
                            <div class="fw-bold small">{{ $task->creator->name ?? 'System' }}</div>
                        </div>
                    </div>
                    <div class="text-end text-muted small">
                        <div>Created: {{ $task->created_at->format('M d, Y') }}</div>
                        @if($task->updated_at != $task->created_at)
                            <div>Updated: {{ $task->updated_at->diffForHumans() }}</div>
                        @endif
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Description</h6>
                @if($task->description)
                    <div class="text-main p-3 rounded" style="background: var(--primary-bg); line-height: 1.6; font-size: 0.95rem;">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                @else
                    <div class="text-center text-muted py-4 rounded" style="background: var(--primary-bg);">
                        <i class="bi bi-card-text fs-3 d-block mb-2 opacity-50"></i>
                        <span class="small font-italic">No description provided for this task.</span>
                    </div>
                @endif
                
                <!-- Progress Bar -->
                <div class="mt-4 pt-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="small fw-bold text-muted text-uppercase" style="letter-spacing: 0.5px;">Progress</span>
                        <span class="small fw-bold {{ $task->progress == 100 ? 'text-success' : 'text-primary' }}">{{ $task->progress }}%</span>
                    </div>
                    <div class="progress" style="height: 8px; background-color: var(--border-color);">
                        <div class="progress-bar {{ $task->progress == 100 ? 'bg-success' : 'bg-primary' }}" role="progressbar" style="width: {{ $task->progress }}%" aria-valuenow="{{ $task->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                
                @if($task->completed_at)
                    <div class="mt-4 alert alert-success bg-success bg-opacity-10 border-0 d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success fs-4 me-3"></i>
                        <div>
                            <div class="fw-bold text-success mb-0">Task Completed</div>
                            <div class="small text-success text-opacity-75">Finished on {{ \Carbon\Carbon::parse($task->completed_at)->format('M d, Y at h:i A') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
