@extends('layouts.master')

@section('title', 'Leads')

@push('custom-css')
<style>
    /* Add extra styles for skeleton specific to this view if needed */
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
        width: 40px;
        height: 40px;
        border-radius: 8px;
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
        <h1 class="h2 fw-bold mb-0">Leads</h1>
        <p class="text-muted mb-0">Track and manage potential customers.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openLeadOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Add Lead
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div class="position-relative" style="max-width: 350px; width: 100%;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5" id="searchInput" placeholder="Search leads by name, email, company...">
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" id="sourceFilter" style="min-width: 150px;">
                    <option value="">All Sources</option>
                    <option value="website">Website</option>
                    <option value="referral">Referral</option>
                    <option value="social_media">Social Media</option>
                    <option value="cold_call">Cold Call</option>
                    <option value="other">Other</option>
                </select>
                <select class="form-select" id="statusFilter" style="min-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="new">New</option>
                    <option value="contacted">Contacted</option>
                    <option value="qualified">Qualified</option>
                    <option value="lost">Lost</option>
                </select>
                <button class="btn btn-outline-secondary d-flex align-items-center" id="refreshBtn" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <div id="table-container">
            @include('leads.partials.table')
        </div>
        
        @include('leads.skeleton')
    </div>
</div>

@include('leads.form')

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    const leadOffcanvas = new bootstrap.Offcanvas(document.getElementById('leadOffcanvas'));
    const $leadForm = $('#leadForm');

    // Function to load leads via AJAX
    function loadLeads(url = '{{ route("leads.index") }}') {
        const search = $('#searchInput').val();
        const status = $('#statusFilter').val();
        const source = $('#sourceFilter').val();
        
        // Show skeleton
        $('#table-container').addClass('d-none');
        $('#leads-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search, status: status, source: source },
            success: function(response) {
                $('#table-container').html(response).removeClass('d-none');
                $('#leads-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load leads. Please try again.', 'error');
                $('#table-container').removeClass('d-none');
                $('#leads-skeleton').addClass('d-none');
            }
        });
    }

    // Event Listeners for Filters
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadLeads(), 500);
    });

    $('#statusFilter, #sourceFilter').on('change', function() {
        loadLeads();
    });

    $('#refreshBtn').on('click', function() {
        loadLeads();
    });

    // Pagination Links Intercept
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadLeads($(this).attr('href'));
    });

    // Open Offcanvas for Create
    window.openLeadOffcanvas = function() {
        $leadForm[0].reset();
        $('#lead_id').val('');
        $('#leadOffcanvasLabel').text('Add New Lead');
        $('.is-invalid').removeClass('is-invalid');
        leadOffcanvas.show();
    };

    // Open Offcanvas for Edit
    $(document).on('click', '.edit-lead-btn', function() {
        const lead = $(this).data('lead');
        $leadForm[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        
        // Populate form
        $('#lead_id').val(lead.id);
        $('#name').val(lead.name);
        $('#email').val(lead.email);
        $('#phone').val(lead.phone);
        $('#company').val(lead.company);
        $('#source').val(lead.source);
        $('#status').val(lead.status);
        $('#priority').val(lead.priority);
        $('#expected_value').val(lead.expected_value);
        $('#remarks').val(lead.remarks);
        
        $('#leadOffcanvasLabel').text('Edit Lead');
        leadOffcanvas.show();
    });

    // Save Lead (Create / Update)
    $('#saveLeadBtn').on('click', function() {
        const id = $('#lead_id').val();
        const url = id ? `/leads/${id}` : '{{ route("leads.store") }}';
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
            data: $leadForm.serialize(),
            success: function(response) {
                leadOffcanvas.hide();
                showToast('Success', response.message, 'success');
                loadLeads();
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for(const field in errors) {
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

    // Delete Lead
    $(document).on('click', '.delete-lead-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        const originalIcon = $btn.html();
        
        confirmAction('Delete Lead?', 'Are you sure you want to delete this lead?', function() {
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
            
            $.ajax({
                url: `/leads/${id}`,
                type: 'DELETE',
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadLeads();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error deleting lead', 'error');
                    $btn.html(originalIcon).prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
