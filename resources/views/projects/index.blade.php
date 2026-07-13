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
                <div class="form-check form-switch d-flex align-items-center ms-2">
                    <input class="form-check-input me-2" type="checkbox" id="trashedFilter" style="width: 2.5em; height: 1.25em; cursor: pointer;">
                    <label class="form-check-label mb-0 text-muted" for="trashedFilter" style="cursor: pointer;"><i class="bi bi-trash3"></i> Trash</label>
                </div>
                <button class="btn btn-outline-secondary d-flex align-items-center ms-2" id="refreshBtn" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <div class="d-none bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded p-3 mb-3 d-flex justify-content-between align-items-center transition-all" id="bulkActionsContainer">
            <div class="d-flex align-items-center">
                <span class="badge bg-primary rounded-pill me-3" id="selectedCount">0</span>
                <span class="text-primary fw-medium">Projects Selected</span>
            </div>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" id="bulkStatusSelect" style="width: 130px;">
                    <option value="">Set Status...</option>
                    <option value="Planning">Planning</option>
                    <option value="Active">Active</option>
                    <option value="On Hold">On Hold</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
                <button class="btn btn-sm btn-primary" id="btnBulkUpdate">Update</button>
                <div class="vr mx-1 opacity-25"></div>
                <button class="btn btn-sm btn-outline-danger d-flex align-items-center" id="btnBulkDelete">
                    <i class="bi bi-trash me-1"></i> Delete Selected
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
        const trashed = $('#trashedFilter').is(':checked') ? 1 : 0;
        
        $('#table-container').addClass('d-none');
        $('#projects-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search, status: status, priority: priority, trashed: trashed },
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

    $('#trashedFilter').on('change', function() {
        if ($(this).is(':checked')) {
            $('#bulkActionsContainer').addClass('d-none');
            $('#statusFilter').val('').prop('disabled', true);
            $('#priorityFilter').val('').prop('disabled', true);
        } else {
            $('#statusFilter').prop('disabled', false);
            $('#priorityFilter').prop('disabled', false);
        }
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

    // Restore Project
    $(document).on('click', '.restore-project-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        const originalIcon = $btn.html();
        
        confirmAction('Restore Project?', 'Are you sure you want to restore this project?', function() {
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
            
            $.ajax({
                url: `/projects/${id}/restore`,
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadProjects();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error restoring project', 'error');
                    $btn.html(originalIcon).prop('disabled', false);
                }
            });
        });
    });

    // Permanent Delete Project
    $(document).on('click', '.force-delete-project-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        const originalIcon = $btn.html();
        
        confirmAction('Permanently Delete Project?', 'This action cannot be undone. Are you absolutely sure?', function() {
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
            
            $.ajax({
                url: `/projects/${id}/force-delete`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadProjects();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error permanently deleting project', 'error');
                    $btn.html(originalIcon).prop('disabled', false);
                }
            });
        });
    });

    // Bulk Actions Logic
    $(document).on('change', '#selectAllProjects', function() {
        $('.project-checkbox').prop('checked', $(this).prop('checked'));
        toggleBulkActions();
    });

    $(document).on('change', '.project-checkbox', function() {
        if (!$(this).prop('checked')) {
            $('#selectAllProjects').prop('checked', false);
        }
        
        if ($('.project-checkbox:checked').length === $('.project-checkbox').length) {
            $('#selectAllProjects').prop('checked', true);
        }
        
        toggleBulkActions();
    });

    function toggleBulkActions() {
        const count = $('.project-checkbox:checked').length;
        if (count > 0) {
            $('#selectedCount').text(count);
            $('#bulkActionsContainer').removeClass('d-none');
        } else {
            $('#bulkActionsContainer').addClass('d-none');
        }
    }

    function getSelectedIds() {
        return $('.project-checkbox:checked').map(function() {
            return $(this).val();
        }).get();
    }

    $('#btnBulkDelete').on('click', function() {
        const ids = getSelectedIds();
        if (ids.length === 0) return;
        
        confirmAction('Bulk Delete', `Are you sure you want to delete ${ids.length} selected projects?`, function() {
            $.ajax({
                url: '{{ route("projects.bulk-delete") }}',
                type: 'POST',
                data: { ids: ids, _token: '{{ csrf_token() }}' },
                success: function(res) {
                    showToast('Success', res.message, 'success');
                    $('#selectAllProjects').prop('checked', false);
                    toggleBulkActions();
                    loadProjects();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Failed to delete projects.', 'error');
                }
            });
        });
    });

    $('#btnBulkUpdate').on('click', function() {
        const ids = getSelectedIds();
        const status = $('#bulkStatusSelect').val();
        
        if (ids.length === 0) return;
        if (!status) {
            showToast('Warning', 'Please select a status to apply.', 'warning');
            return;
        }
        
        $.ajax({
            url: '{{ route("projects.bulk-update") }}',
            type: 'POST',
            data: { ids: ids, status: status, _token: '{{ csrf_token() }}' },
            success: function(res) {
                showToast('Success', res.message, 'success');
                $('#selectAllProjects').prop('checked', false);
                $('#bulkStatusSelect').val('');
                toggleBulkActions();
                loadProjects();
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Failed to update projects.', 'error');
            }
        });
    });
</script>
@endpush
