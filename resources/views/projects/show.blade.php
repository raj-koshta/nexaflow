@extends('layouts.master')

@section('title', 'Project Dashboard')

@push('custom-css')
<style>
    .nav-pills .nav-link {
        color: var(--text-muted);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .nav-pills .nav-link:hover {
        background: rgba(139, 92, 246, 0.1);
        color: var(--accent);
    }
    .nav-pills .nav-link.active {
        background: var(--accent);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('projects.index') }}" class="text-decoration-none">Projects</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($project->name, 20) }}</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">Project Dashboard</h1>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-secondary shadow-sm me-2" onclick="location.href='{{ route('projects.index') }}'">
            <i class="bi bi-arrow-left me-1"></i> Back
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-5 border-end border-md-0" style="border-color: var(--border-color) !important;">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px; font-size: 1.5rem;">
                                {{ substr($project->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="fw-bold mb-1">{{ $project->name }}</h4>
                                <span class="text-muted"><i class="bi bi-building me-1"></i>{{ $project->client->company_name }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 border-end border-md-0 mt-3 mt-md-0" style="border-color: var(--border-color) !important;">
                        <h6 class="text-muted text-uppercase small fw-bold letter-spacing-1">Overall Progress</h6>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="h4 fw-bold mb-0">{{ $project->progress }}%</span>
                        </div>
                        <div class="progress" style="height: 8px; background-color: var(--border-color);">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $project->progress }}%;"></div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-3 mt-md-0">
                        <div class="row">
                            <div class="col-6">
                                <h6 class="text-muted text-uppercase small fw-bold letter-spacing-1">Status</h6>
                                @php
                                    $sColor = match($project->status) {
                                        'Planning' => 'info', 'Active' => 'primary', 'On Hold' => 'warning',
                                        'Completed' => 'success', 'Cancelled' => 'danger', default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} border border-{{ $sColor }} border-opacity-25 rounded-pill px-3 py-2">{{ $project->status }}</span>
                            </div>
                            <div class="col-6">
                                <h6 class="text-muted text-uppercase small fw-bold letter-spacing-1">Priority</h6>
                                @php
                                    $pColor = match($project->priority) {
                                        'Critical' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                                    };
                                @endphp
                                <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded-pill px-3 py-2">{{ $project->priority }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Navigation Tabs -->
    <div class="col-lg-3">
        <ul class="nav nav-pills flex-column mb-4" id="projectTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
                    <i class="bi bi-grid me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="milestones-tab" data-bs-toggle="pill" data-bs-target="#milestones" type="button" role="tab">
                    <i class="bi bi-flag me-2"></i>Milestones <span class="badge bg-secondary ms-2">{{ $project->milestones->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="tasks-tab" data-bs-toggle="pill" data-bs-target="#tasks" type="button" role="tab">
                    <i class="bi bi-check2-square me-2"></i>Tasks (List) <span class="badge bg-secondary ms-2">{{ $project->tasks->count() }}</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="kanban-tab" data-bs-toggle="pill" data-bs-target="#kanban" type="button" role="tab">
                    <i class="bi bi-kanban me-2"></i>Kanban Board
                </button>
            </li>
        </ul>

        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Project Details</h6>
                <p class="text-main small mb-3">{{ $project->description ?: 'No description provided.' }}</p>
                
                <hr style="border-color: var(--border-color);">
                
                <div class="mb-2">
                    <small class="text-muted d-block">Start Date</small>
                    <span class="text-main fw-medium"><i class="bi bi-calendar me-2"></i>{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not Set' }}</span>
                </div>
                <div class="mb-2">
                    <small class="text-muted d-block">Due Date</small>
                    <span class="text-main fw-medium"><i class="bi bi-calendar-check me-2"></i>{{ $project->due_date ? $project->due_date->format('M d, Y') : 'Not Set' }}</span>
                </div>
                <div>
                    <small class="text-muted d-block">Budget</small>
                    <span class="text-main fw-medium"><i class="bi bi-cash me-2"></i>${{ $project->budget ? number_format($project->budget, 2) : '0.00' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Content -->
    <div class="col-lg-9">
        <div class="tab-content" id="projectTabsContent">
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
                            <div class="card-body">
                                <i class="bi bi-check-circle text-success fs-1 mb-2"></i>
                                <h3 class="fw-bold">{{ $project->tasks->where('status', 'Done')->count() }}</h3>
                                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Completed Tasks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
                            <div class="card-body">
                                <i class="bi bi-hourglass-split text-warning fs-1 mb-2"></i>
                                <h3 class="fw-bold">{{ $project->tasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count() }}</h3>
                                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Pending Tasks</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 text-center py-4" style="background: var(--card-bg); border: var(--glass-border);">
                            <div class="card-body">
                                <i class="bi bi-flag text-primary fs-1 mb-2"></i>
                                <h3 class="fw-bold">{{ $project->milestones->count() }}</h3>
                                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Total Milestones</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Milestones Tab -->
            <div class="tab-pane fade" id="milestones" role="tabpanel">
                @include('projects.partials.milestones')
            </div>

            <!-- Tasks Tab -->
            <div class="tab-pane fade" id="tasks" role="tabpanel">
                @include('projects.partials.tasks')
            </div>

            <!-- Kanban Tab -->
            <div class="tab-pane fade" id="kanban" role="tabpanel">
                @include('projects.partials.kanban')
            </div>
        </div>
    </div>
</div>

<!-- AI Task Generator Modal -->
<div class="modal fade" id="aiTaskModal" tabindex="-1" aria-labelledby="aiTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border: var(--glass-border) !important; border-radius: 20px; overflow: hidden;">
            <div class="row g-0">
                <div class="col-md-5 d-none d-md-block" style="background: linear-gradient(135deg, rgba(139, 92, 246, 0.05), rgba(236, 72, 153, 0.05)); border-right: 1px solid var(--border-color);">
                    <div class="d-flex flex-column justify-content-center h-100 p-5 text-center">
                        <div class="mb-4">
                            <i class="bi bi-robot" style="font-size: 3.5rem; background: linear-gradient(135deg, #8b5cf6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 4px 6px rgba(139,92,246,0.3));"></i>
                        </div>
                        <h4 class="fw-bold mb-3" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">AI Task Generator</h4>
                        <p class="text-muted small mb-0" style="line-height: 1.6;">Our intelligent assistant will analyze your project goals and instantly create a structured list of actionable tasks, complete with priority levels and time estimates.</p>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="modal-header border-bottom-0 pb-0 pt-4 px-4">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-btn-filter);"></button>
                    </div>
                    <div class="modal-body p-4 pt-2">
                        <div class="d-md-none mb-4 text-center">
                            <h5 class="fw-bold" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                                <i class="bi bi-robot me-2"></i>AI Task Generator
                            </h5>
                            <p class="text-muted small">Analyze project goals and generate actionable tasks.</p>
                        </div>
                        
                        <div class="mb-4">
                            <label for="ai_task_intent" class="form-label fw-bold small text-uppercase text-muted" style="letter-spacing: 1px;">Project Context & Goals</label>
                            <textarea class="form-control bg-transparent text-main border-secondary border-opacity-25 focus-ring focus-ring-primary p-3" id="ai_task_intent" rows="6" placeholder="Describe the project... e.g. Build a comprehensive dashboard with analytics, user management, and settings." style="border-radius: 12px; resize: none; line-height: 1.5;">{{ $project->description }}</textarea>
                            <div class="form-text small mt-2 text-muted"><i class="bi bi-info-circle me-1 text-primary"></i> The more details you provide, the better the generated tasks will be.</div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-2">
                            <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn text-white rounded-pill px-4 shadow-sm d-flex align-items-center btn-hover-grow" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); border: none;" id="btn-generate-ai-tasks">
                                <i class="bi bi-magic me-2"></i> Generate Magic
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Quick View Modal -->
<div class="modal fade" id="taskQuickViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0 shadow-lg" style="border: var(--glass-border) !important; border-radius: 16px; overflow: hidden;">
            <div id="taskQuickViewContent">
                <!-- Content loaded via AJAX -->
                <div class="p-5 text-center text-muted">
                    <div class="spinner-border mb-3" role="status"></div>
                    <div>Loading task details...</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    // Task Quick View
    $(document).on('click', '.quick-view-task-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        $('#taskQuickViewContent').html(`
            <div class="p-5 text-center text-muted">
                <div class="spinner-border mb-3" role="status"></div>
                <div>Loading task details...</div>
            </div>
        `);
        
        const modal = new bootstrap.Modal(document.getElementById('taskQuickViewModal'));
        modal.show();

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#taskQuickViewContent').html(response);
            },
            error: function() {
                $('#taskQuickViewContent').html(`
                    <div class="p-5 text-center text-danger">
                        <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
                        <h5>Error loading task details</h5>
                        <p class="text-muted">Please try again.</p>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                `);
            }
        });
    });

    // AI Task Generator
    $('#btn-generate-ai-tasks').off('click').on('click', function(e) {
        e.preventDefault();
        const intent = $('#ai_task_intent').val().trim();
        
        if (!intent) {
            showToast('Error', 'Please provide project context first.', 'error');
            return;
        }

        const $btn = $(this);
        const originalHtml = $btn.html();
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Generating...');
        
        $.ajax({
            url: "{{ route('projects.ai-tasks', $project->id) }}",
            type: 'POST',
            data: { 
                _token: '{{ csrf_token() }}',
                intent: intent
            },
            success: function(res) {
                showToast('Success', res.message, 'success');
                $('#aiTaskModal').modal('hide');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                $btn.prop('disabled', false).html(originalHtml);
                showToast('Error', xhr.responseJSON?.message || 'Failed to generate tasks.', 'error');
            }
        });
    });

    // Kanban Board SortableJS Initialization
    $(document).ready(function() {
        const kanbanCols = document.querySelectorAll('.kanban-column');
        kanbanCols.forEach(col => {
            new Sortable(col, {
                group: 'kanban',
                animation: 150,
                ghostClass: 'kanban-ghost',
                onEnd: function (evt) {
                    const itemEl = evt.item;
                    const taskId = itemEl.getAttribute('data-task-id');
                    const newStatus = evt.to.getAttribute('data-status');
                    const oldStatus = evt.from.getAttribute('data-status');

                    if (newStatus !== oldStatus) {
                        // Update column counters instantly
                        updateKanbanCounters();
                        
                        // Send AJAX request to update status
                        $.ajax({
                            url: `/tasks/${taskId}`,
                            type: 'PUT',
                            data: {
                                _token: '{{ csrf_token() }}',
                                status: newStatus
                            },
                            success: function(res) {
                                // Status updated successfully.
                                // We won't reload immediately here so the drag-and-drop feels perfectly smooth,
                                // but we will show a silent success toast.
                                console.log('Task status updated:', newStatus);
                            },
                            error: function(xhr) {
                                showToast('Error', 'Failed to update task status.', 'error');
                                // Revert UI
                                evt.from.insertBefore(itemEl, evt.from.children[evt.oldIndex]);
                                updateKanbanCounters();
                            }
                        });
                    }
                },
            });
        });

        function updateKanbanCounters() {
            kanbanCols.forEach(col => {
                const count = col.querySelectorAll('.kanban-card').length;
                const badge = col.closest('.kanban-container').querySelector('.kanban-count');
                if (badge) badge.textContent = count;
            });
        }
    });

    // Global Reload (since Tasks & Milestones affect Project overall progress, 
 
    // a full reload is safer after a successful CRUD operation for a seamless UX in this V1,
    // though AJAX DOM updates are possible, full reload guarantees progress bars are perfectly in sync).
    
    // Milestones CRUD
    const milestoneOffcanvas = new bootstrap.Offcanvas(document.getElementById('milestoneOffcanvas'));
    const $milestoneForm = $('#milestoneForm');

    window.openMilestoneOffcanvas = function(milestone = null) {
        $milestoneForm[0].reset();
        $milestoneForm.find('.is-invalid').removeClass('is-invalid');
        
        if (milestone) {
            $('#milestoneOffcanvasLabel').text('Edit Milestone');
            $('#milestone_id').val(milestone.id);
            $('#m_title').val(milestone.title);
            $('#m_description').val(milestone.description);
            $('#m_start_date').val(milestone.start_date ? milestone.start_date.split('T')[0] : '');
            $('#m_due_date').val(milestone.due_date ? milestone.due_date.split('T')[0] : '');
            $('#m_status').val(milestone.status);
            if(milestone.status === 'Completed') {
                $('#m_progress').val(100);
            } else {
                $('#m_progress').val(milestone.progress);
            }
        } else {
            $('#milestoneOffcanvasLabel').text('Create Milestone');
            $('#milestone_id').val('');
            $('#m_status').val('Pending');
            $('#m_progress').val(0);
        }
        
        milestoneOffcanvas.show();
    };

    $(document).on('click', '.edit-milestone-btn', function(e) {
        e.preventDefault();
        openMilestoneOffcanvas($(this).data('milestone'));
    });

    $milestoneForm.on('submit', function(e) {
        e.preventDefault();
        const id = $('#milestone_id').val();
        const url = id ? `/milestones/${id}` : '{{ route("milestones.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url, type: method, data: $(this).serialize(),
            success: function(res) {
                showToast('Success', res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error saving milestone', 'error');
            }
        });
    });

    $(document).on('click', '.delete-milestone-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        confirmAction('Delete Milestone?', 'Are you sure? This will NOT delete associated tasks but will orphan them.', function() {
            $.ajax({
                url: `/milestones/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    showToast('Success', res.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        });
    });

    // Tasks CRUD
    const taskOffcanvas = new bootstrap.Offcanvas(document.getElementById('taskOffcanvas'));
    const $taskForm = $('#taskForm');

    window.openTaskOffcanvas = function(task = null) {
        $taskForm[0].reset();
        $taskForm.find('.is-invalid').removeClass('is-invalid');
        
        if (task) {
            $('#taskOffcanvasLabel').text('Edit Task');
            $('#task_id').val(task.id);
            $('#t_title').val(task.title);
            $('#t_description').val(task.description);
            $('#t_milestone_id').val(task.milestone_id);
            $('#t_assigned_to').val(task.assigned_to);
            $('#t_status').val(task.status);
            $('#t_priority').val(task.priority);
            $('#t_start_date').val(task.start_date ? task.start_date.split('T')[0] : '');
            $('#t_due_date').val(task.due_date ? task.due_date.split('T')[0] : '');
            $('#t_estimated_hours').val(task.estimated_hours);
        } else {
            $('#taskOffcanvasLabel').text('Create Task');
            $('#task_id').val('');
            $('#t_status').val('Todo');
            $('#t_priority').val('Medium');
        }
        
        taskOffcanvas.show();
    };

    $(document).on('click', '.edit-task-btn', function(e) {
        e.preventDefault();
        openTaskOffcanvas($(this).data('task'));
    });

    $taskForm.on('submit', function(e) {
        e.preventDefault();
        const id = $('#task_id').val();
        const url = id ? `/tasks/${id}` : '{{ route("tasks.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        $.ajax({
            url: url, type: method, data: $(this).serialize(),
            success: function(res) {
                showToast('Success', res.message, 'success');
                setTimeout(() => location.reload(), 1000);
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error saving task', 'error');
            }
        });
    });

    $(document).on('click', '.delete-task-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        confirmAction('Delete Task?', 'Are you sure?', function() {
            $.ajax({
                url: `/tasks/${id}`, type: 'DELETE', data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    showToast('Success', res.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                }
            });
        });
    });
</script>
@endpush
