@if($projects->count() > 0)
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Project</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Client</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Priority</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Progress</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="border-bottom-0 text-end text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--accent);">
                                        <i class="bi bi-briefcase fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $project->name }}</h6>
                                        <small class="text-muted">{{ $project->project_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                        {{ substr($project->client->company_name, 0, 1) }}
                                    </div>
                                    <span class="fw-medium">{{ $project->client->company_name }}</span>
                                </div>
                            </td>
                            <td>
                                @php
                                    $pColor = match($project->priority) {
                                        'Critical' => 'danger',
                                        'High' => 'warning',
                                        'Medium' => 'info',
                                        'Low' => 'secondary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded-pill px-3">
                                    {{ $project->priority }}
                                </span>
                            </td>
                            <td style="width: 20%;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <small class="text-muted">{{ $project->progress }}%</small>
                                </div>
                                <div class="progress" style="height: 6px; background-color: var(--border-color);">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%;" aria-valuenow="{{ $project->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </td>
                            <td>
                                @php
                                    $sColor = match($project->status) {
                                        'Planning' => 'info',
                                        'Active' => 'primary',
                                        'On Hold' => 'warning',
                                        'Completed' => 'success',
                                        'Cancelled' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} border border-{{ $sColor }} border-opacity-25 rounded-pill px-3">
                                    {{ $project->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-link text-primary p-1" title="View Dashboard">
                                    <i class="bi bi-eye fs-5"></i>
                                </a>
                                <button class="btn btn-sm btn-link text-muted edit-project-btn p-1" data-project="{{ json_encode($project) }}" title="Edit Project">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-project-btn p-1" data-id="{{ $project->id }}" title="Delete Project">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: var(--border-color) !important;">
                <div class="text-muted small">
                    Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} entries
                </div>
                <div>
                    {{ $projects->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                <i class="bi bi-briefcase"></i>
            </div>
            <h4 class="fw-bold">No projects found</h4>
            <p class="text-muted mb-4">Start organizing work by creating your first project.</p>
            <button class="btn btn-primary px-4" onclick="openProjectOffcanvas()">
                <i class="bi bi-plus-lg me-2"></i>Create Project
            </button>
        </div>
    </div>
@endif
