@extends('layouts.master')

@section('title', 'Activities')

@push('custom-css')
<style>
    /* Skeleton specific styles */
    .skeleton-text {
        height: 16px;
        background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
        border-radius: 4px;
    }
    
    [data-bs-theme="light"] .skeleton-text {
        background: linear-gradient(90deg, rgba(0,0,0,0.05) 25%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.05) 75%);
        background-size: 200% 100%;
    }

    .skeleton-avatar {
        background: linear-gradient(90deg, rgba(255,255,255,0.05) 25%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0.05) 75%);
        background-size: 200% 100%;
        animation: loading 1.5s infinite;
    }

    [data-bs-theme="light"] .skeleton-avatar {
        background: linear-gradient(90deg, rgba(0,0,0,0.05) 25%, rgba(0,0,0,0.1) 50%, rgba(0,0,0,0.05) 75%);
        background-size: 200% 100%;
    }

    @keyframes loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Activity Timeline</h1>
        <p class="text-muted mb-0">Track all interactions across clients and leads.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openActivityOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Log Activity
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-3">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="position-relative">
                            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <input type="text" class="form-control ps-5" id="searchInput" placeholder="Search keywords...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="entityFilter">
                            <option value="">All Clients & Leads</option>
                            <optgroup label="Clients">
                                @foreach($clients as $client)
                                    <option value="client_{{ $client->id }}">{{ $client->company_name }}</option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Leads">
                                @foreach($leads as $lead)
                                    <option value="lead_{{ $lead->id }}">{{ $lead->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary w-100 h-100" id="refreshBtn" title="Refresh Timeline">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="timeline-container">
            @include('activities.partials.timeline')
        </div>
        
        @include('activities.skeleton')
    </div>
</div>

@include('activities.form')

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    const activityOffcanvas = new bootstrap.Offcanvas(document.getElementById('activityOffcanvas'));
    const $activityForm = $('#activityForm');

    // Function to load activities via AJAX
    function loadActivities(url = '{{ route("activities.index") }}') {
        const search = $('#searchInput').val();
        const type = $('#typeFilter').val();
        
        const entityVal = $('#entityFilter').val();
        let client_id = '';
        let lead_id = '';
        
        if (entityVal.startsWith('client_')) {
            client_id = entityVal.replace('client_', '');
        } else if (entityVal.startsWith('lead_')) {
            lead_id = entityVal.replace('lead_', '');
        }
        
        // Show skeleton
        $('#timeline-container').addClass('d-none');
        $('#activities-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search, type, client_id, lead_id },
            success: function(response) {
                $('#timeline-container').html(response).removeClass('d-none');
                $('#activities-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load timeline. Please try again.', 'error');
                $('#timeline-container').removeClass('d-none');
                $('#activities-skeleton').addClass('d-none');
            }
        });
    }

    // Toggle Client/Lead dropdowns in form
    $('input[name="entity_type"]').on('change', function() {
        if($(this).val() === 'client') {
            $('#client_select_wrapper').removeClass('d-none');
            $('#lead_select_wrapper').addClass('d-none');
            $('#lead_id').val('');
        } else {
            $('#client_select_wrapper').addClass('d-none');
            $('#lead_select_wrapper').removeClass('d-none');
            $('#client_id').val('');
        }
    });

    // Event Listeners for Filters
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadActivities(), 500);
    });

    $('#typeFilter, #entityFilter').on('change', function() {
        loadActivities();
    });

    $('#refreshBtn').on('click', function() {
        loadActivities();
    });

    // Pagination Links Intercept
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadActivities($(this).attr('href'));
    });

    // Format datetime for datetime-local input
    function formatDateTime(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        // Adjust for local timezone offset
        d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
        return d.toISOString().slice(0, 16);
    }

    // Open Offcanvas for Create
    window.openActivityOffcanvas = function() {
        $activityForm[0].reset();
        $('#activity_id').val('');
        
        // Default to client and current time
        $('#link_client').prop('checked', true).trigger('change');
        $('#activity_date').val(formatDateTime(new Date()));
        
        $('#activityOffcanvasLabel').text('Log Activity');
        $('.is-invalid').removeClass('is-invalid');
        activityOffcanvas.show();
    };

    // Open Offcanvas for Edit
    $(document).on('click', '.edit-activity-btn', function(e) {
        e.preventDefault();
        const activity = $(this).data('activity');
        $activityForm[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        
        // Populate form
        $('#activity_id').val(activity.id);
        $('#title').val(activity.title);
        $('#description').val(activity.description);
        $('#type').val(activity.type);
        $('#activity_date').val(formatDateTime(activity.activity_date));
        
        if (activity.lead_id) {
            $('#link_lead').prop('checked', true).trigger('change');
            $('#lead_id').val(activity.lead_id);
        } else {
            $('#link_client').prop('checked', true).trigger('change');
            $('#client_id').val(activity.client_id);
        }
        
        $('#activityOffcanvasLabel').text('Edit Activity');
        activityOffcanvas.show();
    });

    // Save Activity (Create / Update)
    $('#saveActivityBtn').on('click', function() {
        const id = $('#activity_id').val();
        const url = id ? `/activities/${id}` : '{{ route("activities.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        const $btn = $(this);
        const $label = $btn.find('.indicator-label');
        const $progress = $btn.find('.indicator-progress');
        
        // Reset validation
        $('.is-invalid').removeClass('is-invalid');
        
        $btn.prop('disabled', true);
        $label.addClass('d-none');
        $progress.removeClass('d-none');

        $.ajax({
            url: url,
            type: method,
            data: $activityForm.serialize(),
            success: function(response) {
                activityOffcanvas.hide();
                showToast('Success', response.message, 'success');
                loadActivities();
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    
                    // Handle entity selection errors gracefully
                    if (errors.client_id || errors.lead_id) {
                        const activeType = $('input[name="entity_type"]:checked').val();
                        const field = activeType === 'client' ? 'client_id' : 'lead_id';
                        const message = activeType === 'client' ? 'Please select a client.' : 'Please select a lead.';
                        
                        const $input = $(`#${field}`);
                        $input.addClass('is-invalid');
                        $input.siblings('.invalid-feedback').text(message);
                    }
                    
                    for(const field in errors) {
                        if (field === 'client_id' || field === 'lead_id') continue;
                        
                        const $input = $(`#${field}`);
                        $input.addClass('is-invalid');
                        $input.siblings('.invalid-feedback').text(errors[field][0]);
                    }
                } else {
                    showToast('Error', xhr.responseJSON?.message || 'Something went wrong!', 'error');
                }
            },
            complete: function() {
                $btn.prop('disabled', false);
                $label.removeClass('d-none');
                $progress.addClass('d-none');
            }
        });
    });

    // Delete Activity
    $(document).on('click', '.delete-activity-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        confirmAction('Delete Activity?', 'Are you sure you want to remove this from the timeline?', function() {
            $.ajax({
                url: `/activities/${id}`,
                type: 'DELETE',
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadActivities();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error deleting activity', 'error');
                }
            });
        });
    });
</script>
@endpush
