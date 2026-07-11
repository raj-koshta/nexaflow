<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Tasks</h5>
    <div class="d-flex gap-2">
        <button class="btn btn-sm text-white px-3 shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #ec4899); border: none;" data-bs-toggle="modal" data-bs-target="#aiTaskModal">
            <i class="bi bi-stars me-1"></i> Generate with AI
        </button>
        <button class="btn btn-sm btn-primary" onclick="openTaskOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Add Task
        </button>
    </div>
</div>

<div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="border-bottom-0 text-uppercase text-muted ps-4" style="font-size: 0.75rem; letter-spacing: 1px;">Task Title</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Assignee</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Priority</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                        <th class="border-bottom-0 text-end text-uppercase text-muted pe-4" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($project->tasks as $task)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td class="py-3 ps-4">
                            <h6 class="mb-1 fw-bold">{{ $task->title }}</h6>
                            <div class="text-muted small">
                                @if($task->milestone)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary me-2"><i class="bi bi-flag me-1"></i>{{ $task->milestone->title }}</span>
                                @endif
                                <i class="bi bi-calendar me-1"></i> Due: {{ $task->due_date ? $task->due_date->format('M d') : 'N/A' }}
                            </div>
                        </td>
                        <td>
                            @if($task->assignee)
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 0.75rem; fw-bold">
                                        {{ substr($task->assignee->name, 0, 1) }}
                                    </div>
                                    <span class="small">{{ $task->assignee->name }}</span>
                                </div>
                            @else
                                <span class="text-muted small font-italic">Unassigned</span>
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
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-check2-square fs-2 d-block mb-3 opacity-50"></i>
                            <h6 class="fw-bold">No tasks found</h6>
                            <p class="mb-0">Add tasks to track specific action items.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

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
            <input type="hidden" name="project_id" value="{{ $project->id }}">

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Task Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="t_title" name="title" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Milestone</label>
                <select class="form-select" id="t_milestone_id" name="milestone_id">
                    <option value="">No Milestone</option>
                    @foreach($project->milestones as $milestone)
                        <option value="{{ $milestone->id }}">{{ $milestone->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Assignee</label>
                <select class="form-select" id="t_assigned_to" name="assigned_to">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
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
@endpush
