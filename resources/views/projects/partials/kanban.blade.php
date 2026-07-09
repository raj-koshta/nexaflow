<style>
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

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Kanban Board</h5>
    <button class="btn btn-sm btn-primary" onclick="openTaskOffcanvas()">
        <i class="bi bi-plus-lg me-1"></i> Add Task
    </button>
</div>

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
                    {{ $project->tasks->where('status', $status)->count() }}
                </span>
            </div>
            <div class="kanban-column" data-status="{{ $status }}">
                @foreach($project->tasks->where('status', $status) as $task)
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
                            <button class="btn btn-sm btn-link text-muted p-0 edit-task-btn" data-task="{{ json_encode($task) }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                        
                        <h6 class="fw-bold mb-2 fs-6 text-main" style="line-height: 1.4;">{{ $task->title }}</h6>
                        
                        @if($task->milestone)
                            <div class="mb-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary w-100 text-start text-truncate" title="{{ $task->milestone->title }}">
                                    <i class="bi bi-flag me-1"></i>{{ Str::limit($task->milestone->title, 20) }}
                                </span>
                            </div>
                        @endif

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
