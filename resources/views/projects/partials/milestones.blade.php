<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Project Milestones</h5>
    <button class="btn btn-sm btn-primary" onclick="openMilestoneOffcanvas()">
        <i class="bi bi-plus-lg me-1"></i> Add Milestone
    </button>
</div>

<div class="row g-3">
    @forelse($project->milestones as $milestone)
        <div class="col-12">
            <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                                <i class="bi bi-flag-fill fs-5"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $milestone->title }}</h5>
                                <div class="text-muted small">
                                    <i class="bi bi-calendar me-1"></i> Due: {{ $milestone->due_date ? $milestone->due_date->format('M d, Y') : 'No Date' }} &bull;
                                    <span class="badge bg-{{ $milestone->status === 'Completed' ? 'success' : ($milestone->status === 'In Progress' ? 'primary' : 'secondary') }} bg-opacity-10 text-{{ $milestone->status === 'Completed' ? 'success' : ($milestone->status === 'In Progress' ? 'primary' : 'secondary') }} border border-opacity-25 rounded-pill px-2">
                                        {{ $milestone->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="background: var(--secondary-bg); border: var(--glass-border);">
                                <li><a class="dropdown-item edit-milestone-btn" href="#" data-milestone="{{ json_encode($milestone) }}" style="color: var(--text-main);"><i class="bi bi-pencil me-2"></i>Edit</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger delete-milestone-btn" href="#" data-id="{{ $milestone->id }}"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                    
                    @if($milestone->description)
                        <p class="text-main mb-3">{{ $milestone->description }}</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted fw-bold">Milestone Progress</small>
                        <small class="text-muted">{{ $milestone->progress }}%</small>
                    </div>
                    <div class="progress" style="height: 6px; background-color: var(--border-color);">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $milestone->progress }}%;"></div>
                    </div>
                    
                    <div class="mt-3 text-end">
                        <small class="text-muted">{{ $milestone->tasks->count() }} Tasks associated</small>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <i class="bi bi-flag text-muted fs-1 opacity-50 mb-3 d-block"></i>
            <h5 class="fw-bold text-muted">No milestones yet</h5>
            <p class="text-muted">Break your project down into major phases.</p>
        </div>
    @endforelse
</div>

@push('modals')
<!-- Milestone Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="milestoneOffcanvas" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important;">
        <h5 class="offcanvas-title fw-bold" id="milestoneOffcanvasLabel">Create Milestone</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" style="filter: var(--close-btn-filter);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="milestoneForm">
            @csrf
            <input type="hidden" name="id" id="milestone_id">
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="m_title" name="title" required>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Status <span class="text-danger">*</span></label>
                <select class="form-select" id="m_status" name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Start Date</label>
                    <input type="date" class="form-control" id="m_start_date" name="start_date">
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Due Date</label>
                    <input type="date" class="form-control" id="m_due_date" name="due_date">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small text-uppercase">Description</label>
                <textarea class="form-control" id="m_description" name="description" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Milestone</button>
            </div>
        </form>
    </div>
</div>
@endpush
