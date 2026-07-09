@extends('layouts.master')

@section('title', 'Projects')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Projects</h1>
        <p class="text-muted mb-0">Manage work, track milestones, and monitor budgets.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openProjectOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> New Project
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div class="position-relative" style="max-width: 350px; width: 100%;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5" id="searchInput" placeholder="Search projects or clients...">
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" id="statusFilter" style="min-width: 140px;">
                    <option value="">All Statuses</option>
                    <option value="Planning">Planning</option>
                    <option value="Active">Active</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Completed">Completed</option>
                </select>
                <select class="form-select" id="priorityFilter" style="min-width: 140px;">
                    <option value="">All Priorities</option>
                    <option value="Critical">Critical</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>
                <button class="btn btn-outline-secondary d-flex align-items-center" id="refreshBtn" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <!-- Dynamic Container for Table -->
        <div id="table-container">
            @include('projects.partials.table')
        </div>
        
        <!-- Skeleton Loader (Hidden by Default) -->
        <div id="projects-skeleton" class="d-none">
            @include('projects.skeleton')
        </div>
    </div>
</div>

@push('modals')
    @include('projects.form')
@endpush

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    const projectOffcanvas = new bootstrap.Offcanvas(document.getElementById('projectOffcanvas'));
    const $projectForm = $('#projectForm');

    function loadProjects(url = '{{ route("projects.index") }}') {
        const search = $('#searchInput').val();
        const status = $('#statusFilter').val();
        const priority = $('#priorityFilter').val();
        
        $('#table-container').addClass('d-none');
        $('#projects-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search, status: status, priority: priority },
            success: function(response) {
                $('#table-container').html(response).removeClass('d-none');
                $('#projects-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load projects. Please try again.', 'error');
                $('#table-container').removeClass('d-none');
                $('#projects-skeleton').addClass('d-none');
            }
        });
    }

    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadProjects(), 500);
    });

    $('#statusFilter, #priorityFilter').on('change', function() {
        loadProjects();
    });

    $('#refreshBtn').on('click', function() {
        loadProjects();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadProjects($(this).attr('href'));
    });

    window.openProjectOffcanvas = function(project = null) {
        $projectForm[0].reset();
        $projectForm.find('.is-invalid').removeClass('is-invalid');
        
        if (project) {
            $('#projectOffcanvasLabel').text('Edit Project');
            $('#project_id').val(project.id);
            $('#name').val(project.name);
            $('#client_id').val(project.client_id);
            $('#status').val(project.status);
            $('#priority').val(project.priority);
            $('#start_date').val(project.start_date ? project.start_date.split('T')[0] : '');
            $('#due_date').val(project.due_date ? project.due_date.split('T')[0] : '');
            $('#budget').val(project.budget);
            $('#description').val(project.description);
            $('#progress').val(project.progress);
            $('#progress-container').show();
        } else {
            $('#projectOffcanvasLabel').text('Create Project');
            $('#project_id').val('');
            $('#status').val('Planning');
            $('#priority').val('Medium');
            $('#progress-container').hide();
        }
        
        projectOffcanvas.show();
    };

    $(document).on('click', '.edit-project-btn', function(e) {
        e.preventDefault();
        const project = $(this).data('project');
        openProjectOffcanvas(project);
    });

    $projectForm.on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#project_id').val();
        const url = id ? `/projects/${id}` : '{{ route("projects.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        const formData = $(this).serialize();
        
        $('#form-loading').removeClass('d-none').addClass('d-flex');
        $projectForm.find('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: url,
            type: method,
            data: formData,
            success: function(response) {
                showToast('Success', response.message, 'success');
                projectOffcanvas.hide();
                loadProjects();
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const field in errors) {
                        const $input = $(`#${field}`);
                        $input.addClass('is-invalid');
                        $input.siblings('.invalid-feedback').text(errors[field][0]);
                    }
                } else {
                    showToast('Error', xhr.responseJSON?.message || 'Something went wrong.', 'error');
                }
            },
            complete: function() {
                $('#form-loading').removeClass('d-flex').addClass('d-none');
            }
        });
    });

    $(document).on('click', '.delete-project-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        confirmAction('Delete Project?', 'Are you sure you want to delete this project? This will soft-delete the record.', function() {
            $.ajax({
                url: `/projects/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadProjects();
                },
                error: function(xhr) {
                    showToast('Error', 'Failed to delete project.', 'error');
                }
            });
        });
    });
</script>
@endpush
