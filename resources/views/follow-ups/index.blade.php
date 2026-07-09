@extends('layouts.master')

@section('title', 'Follow Ups')

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
    
    /* Nav Pills for Filters */
    .nav-pills .nav-link {
        color: var(--text-muted);
        border-radius: 8px;
        padding: 0.5rem 1rem;
        transition: all 0.2s ease;
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
        <h1 class="h2 fw-bold mb-0">Follow Ups</h1>
        <p class="text-muted mb-0">Manage your scheduled interactions and tasks.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openFollowUpOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Schedule Follow Up
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <!-- Tabs -->
            <ul class="nav nav-pills" id="followUpTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-overdue" data-category="overdue" type="button" role="tab">
                        Overdue
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-today" data-category="today" type="button" role="tab">
                        Today
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-upcoming" data-category="upcoming" type="button" role="tab">
                        Upcoming
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-completed" data-category="completed" type="button" role="tab">
                        Completed
                    </button>
                </li>
            </ul>
            
            <!-- Search -->
            <div class="position-relative" style="min-width: 250px;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5 rounded-pill bg-transparent border-secondary border-opacity-25" id="searchInput" placeholder="Search remarks or names...">
            </div>
        </div>

        <div id="list-container">
            @include('follow-ups.partials.list')
        </div>
        
        @include('follow-ups.skeleton')
    </div>
</div>

@include('follow-ups.form')

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    let currentCategory = 'today';
    const followUpOffcanvas = new bootstrap.Offcanvas(document.getElementById('followUpOffcanvas'));
    const $followUpForm = $('#followUpForm');

    // Function to load follow-ups via AJAX
    function loadFollowUps(url = '{{ route("follow-ups.index") }}') {
        const search = $('#searchInput').val();
        
        // Show skeleton
        $('#list-container').addClass('d-none');
        $('#followups-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { category: currentCategory, search },
            success: function(response) {
                $('#list-container').html(response).removeClass('d-none');
                $('#followups-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load follow ups. Please try again.', 'error');
                $('#list-container').removeClass('d-none');
                $('#followups-skeleton').addClass('d-none');
            }
        });
    }

    // Tab Switching
    $('.nav-pills .nav-link').on('click', function() {
        $('.nav-pills .nav-link').removeClass('active');
        $(this).addClass('active');
        currentCategory = $(this).data('category');
        
        // Optional: Reset search on tab change
        // $('#searchInput').val('');
        
        loadFollowUps();
    });

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
        searchTimeout = setTimeout(() => loadFollowUps(), 500);
    });

    // Pagination Links Intercept
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadFollowUps($(this).attr('href'));
    });

    // Open Offcanvas for Create
    window.openFollowUpOffcanvas = function() {
        $followUpForm[0].reset();
        $('#followup_id').val('');
        
        // Default to client and current date
        $('#link_client').prop('checked', true).trigger('change');
        
        const today = new Date();
        $('#follow_date').val(today.toISOString().split('T')[0]);
        $('#follow_time').val(today.toTimeString().slice(0,5));
        
        $('#status_wrapper').addClass('d-none');
        $('#status').val('Pending');
        
        $('#followUpOffcanvasLabel').text('Schedule Follow Up');
        $('.is-invalid').removeClass('is-invalid');
        followUpOffcanvas.show();
    };

    // Open Offcanvas for Edit
    $(document).on('click', '.edit-followup-btn', function(e) {
        e.preventDefault();
        const followUp = $(this).data('followup');
        $followUpForm[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        
        // Populate form
        $('#followup_id').val(followUp.id);
        $('#remarks').val(followUp.remarks);
        
        // Format dates
        $('#follow_date').val(followUp.follow_date.split('T')[0]);
        if(followUp.follow_time) {
            $('#follow_time').val(followUp.follow_time.substring(0, 5));
        }
        
        if (followUp.lead_id) {
            $('#link_lead').prop('checked', true).trigger('change');
            $('#lead_id').val(followUp.lead_id);
        } else {
            $('#link_client').prop('checked', true).trigger('change');
            $('#client_id').val(followUp.client_id);
        }
        
        $('#assigned_to').val(followUp.assigned_to);
        
        // Show status for edit mode
        $('#status_wrapper').removeClass('d-none');
        $('#status').val(followUp.status);
        
        $('#followUpOffcanvasLabel').text('Edit Follow Up');
        followUpOffcanvas.show();
    });

    // Save Follow Up (Create / Update)
    $('#saveFollowUpBtn').on('click', function() {
        const id = $('#followup_id').val();
        const url = id ? `/follow-ups/${id}` : '{{ route("follow-ups.store") }}';
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
            data: $followUpForm.serialize(),
            success: function(response) {
                followUpOffcanvas.hide();
                showToast('Success', response.message, 'success');
                loadFollowUps();
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    
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

    // Mark as Completed
    $(document).on('click', '.mark-complete-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $btn = $(this);
        const originalContent = $btn.html();
        
        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
        
        $.ajax({
            url: `/follow-ups/${id}/complete`,
            type: 'POST',
            success: function(response) {
                showToast('Success', response.message, 'success');
                loadFollowUps();
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error completing follow up', 'error');
                $btn.html(originalContent).prop('disabled', false);
            }
        });
    });

    // Delete Follow Up
    $(document).on('click', '.delete-followup-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        confirmAction('Delete Schedule?', 'Are you sure you want to remove this follow up?', function() {
            $.ajax({
                url: `/follow-ups/${id}`,
                type: 'DELETE',
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadFollowUps();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error deleting follow up', 'error');
                }
            });
        });
    });
</script>
@endpush
