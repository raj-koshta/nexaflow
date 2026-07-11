@extends('layouts.master')

@section('title', 'Global Tasks Dashboard')

@push('custom-css')
<style>
    .nav-pills .nav-link {
        color: var(--text-muted);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-weight: 500;
        margin-right: 0.5rem;
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
    
    .kanban-board {
        display: flex;
        gap: 1rem;
        overflow-x: auto;
        padding-bottom: 1rem;
        min-height: 500px;
    }
    .kanban-container {
        flex: 1;
        min-width: 280px;
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 12px;
        display: flex;
        flex-direction: column;
    }
    .kanban-header {
        padding: 1rem;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .kanban-column {
        padding: 1rem;
        flex: 1;
        overflow-y: auto;
        min-height: 150px;
    }
    .kanban-card {
        background: var(--primary-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        cursor: grab;
        transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .kanban-card:active {
        cursor: grabbing;
    }
    .kanban-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .kanban-ghost {
        opacity: 0.4;
        background: var(--secondary-bg);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Tasks Dashboard</h1>
        <p class="text-muted">Manage all your tasks across all projects.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openTaskOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Add Task
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-3" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-list-task text-primary fs-2 mb-2"></i>
                <h3 class="fw-bold">{{ $tasks->count() }}</h3>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Total Tasks</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-3" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-exclamation-triangle text-danger fs-2 mb-2"></i>
                <h3 class="fw-bold">{{ $tasks->where('status', '!=', 'Done')->where('due_date', '<', now())->count() }}</h3>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Overdue</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-3" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-hourglass-split text-warning fs-2 mb-2"></i>
                <h3 class="fw-bold">{{ $tasks->whereIn('status', ['Todo', 'In Progress', 'Review'])->count() }}</h3>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Pending</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 text-center py-3" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <i class="bi bi-check-circle text-success fs-2 mb-2"></i>
                <h3 class="fw-bold">{{ $tasks->where('status', 'Done')->count() }}</h3>
                <p class="text-muted mb-0 text-uppercase small letter-spacing-1">Completed</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-pills mb-4" id="tasksTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="list-tab" data-bs-toggle="pill" data-bs-target="#list" type="button" role="tab">
            <i class="bi bi-list-ul me-2"></i>List View
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="kanban-tab" data-bs-toggle="pill" data-bs-target="#kanban" type="button" role="tab">
            <i class="bi bi-kanban me-2"></i>Kanban Board
        </button>
    </li>
</ul>

<div class="tab-content" id="tasksTabsContent">
    <!-- List View -->
    <div class="tab-pane fade show active" id="list" role="tabpanel">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                        <thead style="background: rgba(255,255,255,0.02);">
                            <tr>
                                <th class="border-bottom-0 text-uppercase text-muted ps-4" style="font-size: 0.75rem; letter-spacing: 1px;">Task Title</th>
                                <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Project</th>
                                <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Assignee</th>
                                <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Priority</th>
                                <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                                <th class="border-bottom-0 text-end text-uppercase text-muted pe-4" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                            <tr style="border-bottom: 1px solid var(--border-color);">
                                <td class="py-3 ps-4">
                                    <h6 class="mb-1 fw-bold">{{ $task->title }}</h6>
                                    <div class="text-muted small">
                                        <i class="bi bi-calendar me-1"></i> Due: {{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('projects.show', $task->project_id) }}" class="text-decoration-none d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                            {{ substr($task->project->name, 0, 1) }}
                                        </div>
                                        <span class="small">{{ Str::limit($task->project->name, 20) }}</span>
                                    </a>
                                </td>
                                <td>
                                    @if($task->assignee)
                                        <div class="d-flex align-items-center">
                                            <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 0.75rem; font-weight: bold;">
                                                {{ substr($task->assignee->name, 0, 1) }}
                                            </div>
                                            <span class="small">{{ $task->assignee->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted small fst-italic">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $pColor = match($task->priority) {
                                            'Urgent' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded-pill px-2">{{ $task->priority }}</span>
                                </td>
                                <td>
                                    @php
                                        $sColor = match($task->status) {
                                            'Todo' => 'secondary', 'In Progress' => 'primary', 'Review' => 'warning', 'Done' => 'success', default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} border border-{{ $sColor }} border-opacity-25 rounded-pill px-2">{{ $task->status }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <button class="btn btn-sm btn-link text-primary p-1 quick-view-task-btn" data-url="{{ route('tasks.show', $task->id) }}" title="Quick View Task">
                                        <i class="bi bi-eye fs-5"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-muted edit-task-btn p-1" data-task="{{ json_encode($task) }}">
                                        <i class="bi bi-pencil-square fs-5"></i>
                                    </button>
                                    <button class="btn btn-sm btn-link text-danger delete-task-btn p-1" data-id="{{ $task->id }}">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-check2-square fs-2 d-block mb-3 opacity-50"></i>
                                    <h6 class="fw-bold">No tasks found</h6>
                                    <p class="mb-0">Create tasks to stay organized.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Kanban View -->
    <div class="tab-pane fade" id="kanban" role="tabpanel">
        @php
            $statuses = [
                'Todo' => ['title' => 'To Do', 'icon' => 'bi-circle', 'color' => 'secondary'],
                'In Progress' => ['title' => 'In Progress', 'icon' => 'bi-play-circle', 'color' => 'primary'],
                'Review' => ['title' => 'Review', 'icon' => 'bi-eye', 'color' => 'warning'],
                'Done' => ['title' => 'Done', 'icon' => 'bi-check-circle', 'color' => 'success']
            ];
        @endphp

        <div class="kanban-board">
            @foreach($statuses as $status => $meta)
                <div class="kanban-container shadow-sm">
                    <div class="kanban-header">
                        <h6 class="mb-0 fw-bold d-flex align-items-center">
                            <i class="bi {{ $meta['icon'] }} text-{{ $meta['color'] }} me-2"></i>
                            {{ $meta['title'] }}
                        </h6>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill kanban-count">
                            {{ $tasks->where('status', $status)->count() }}
                        </span>
                    </div>
                    <div class="kanban-column" data-status="{{ $status }}">
                        @foreach($tasks->where('status', $status) as $task)
                            <div class="kanban-card shadow-sm" data-task-id="{{ $task->id }}">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    @php
                                        $pColor = match($task->priority) {
                                            'Urgent' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} border border-{{ $pColor }} border-opacity-25 rounded px-2" style="font-size: 0.65rem;">
                                        {{ strtoupper($task->priority) }}
                                    </span>
                                    <div class="d-flex gap-1">
                                        <button class="btn btn-sm btn-link text-primary p-0 quick-view-task-btn" data-url="{{ route('tasks.show', $task->id) }}" title="Quick View Task">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-link text-muted p-0 edit-task-btn" data-task="{{ json_encode($task) }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <h6 class="fw-bold mb-2 fs-6 text-main" style="line-height: 1.4;">{{ $task->title }}</h6>
                                
                                <div class="mb-3">
                                    <span class="badge bg-primary bg-opacity-10 text-primary w-100 text-start text-truncate" title="{{ $task->project->name }}">
                                        <i class="bi bi-briefcase me-1"></i>{{ Str::limit($task->project->name, 20) }}
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top" style="border-color: var(--border-color) !important;">
                                    <div class="text-muted small" title="Due Date">
                                        <i class="bi bi-calendar me-1"></i> {{ $task->due_date ? $task->due_date->format('M d') : 'N/A' }}
                                    </div>
                                    
                                    @if($task->assignee)
                                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center fw-bold" title="Assigned to {{ $task->assignee->name }}" style="width: 24px; height: 24px; font-size: 0.65rem;">
                                            {{ substr($task->assignee->name, 0, 1) }}
                                        </div>
                                    @else
                                        <i class="bi bi-person-x text-muted" title="Unassigned"></i>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('modals')
<!-- Task Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="taskOffcanvas" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important;">
        <h5 class="offcanvas-title fw-bold" id="taskOffcanvasLabel">Create Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" style="filter: var(--close-btn-filter);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="taskForm">
            @csrf
            <input type="hidden" name="id" id="task_id">

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Project <span class="text-danger">*</span></label>
                <select class="form-select" id="t_project_id" name="project_id" required>
                    <option value="">Select Project</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Task Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="t_title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Assignee</label>
                <select class="form-select" id="t_assigned_to" name="assigned_to">
                    <option value="">Unassigned</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="t_status" name="status" required>
                        <option value="Todo">Todo</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Review">Review</option>
                        <option value="Done">Done</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Priority <span class="text-danger">*</span></label>
                    <select class="form-select" id="t_priority" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Start Date</label>
                    <input type="date" class="form-control" id="t_start_date" name="start_date">
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Due Date</label>
                    <input type="date" class="form-control" id="t_due_date" name="due_date">
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Estimated Hours</label>
                <input type="number" step="0.5" class="form-control" id="t_estimated_hours" name="estimated_hours">
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small text-uppercase">Description</label>
                <textarea class="form-control" id="t_description" name="description" rows="3"></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Task</button>
            </div>
        </form>
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
@endpush

@push('custom-scripts')
<!-- Include SortableJS for Kanban -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    // Kanban Board SortableJS Initialization
    $(document).ready(function() {
        if(typeof Sortable !== 'undefined') {
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
        }
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
            $('#t_project_id').val(task.project_id);
            $('#t_title').val(task.title);
            $('#t_description').val(task.description);
            $('#t_assigned_to').val(task.assigned_to);
            $('#t_status').val(task.status);
            $('#t_priority').val(task.priority);
            $('#t_start_date').val(task.start_date ? task.start_date.split('T')[0] : '');
            $('#t_due_date').val(task.due_date ? task.due_date.split('T')[0] : '');
            $('#t_estimated_hours').val(task.estimated_hours);
        } else {
            $('#taskOffcanvasLabel').text('Create Task');
            $('#task_id').val('');
            $('#t_project_id').val('');
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
</script>
@endpush
