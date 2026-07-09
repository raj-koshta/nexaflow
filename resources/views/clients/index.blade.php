@extends('layouts.master')

@section('title', 'Clients')

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
        <h1 class="h2 fw-bold mb-0">Clients</h1>
        <p class="text-muted mb-0">Manage your customers and organizations.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openClientOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Add Client
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div class="position-relative" style="max-width: 350px; width: 100%;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5" id="searchInput" placeholder="Search clients by name, email, phone...">
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" id="statusFilter" style="min-width: 150px;">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <button class="btn btn-outline-secondary d-flex align-items-center" id="refreshBtn" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <div id="table-container">
            @include('clients.partials.table')
        </div>
        
        @include('clients.skeleton')
    </div>
</div>

@push('modals')
    @include('clients.form')
@endpush

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    const clientOffcanvas = new bootstrap.Offcanvas(document.getElementById('clientOffcanvas'));
    const $clientForm = $('#clientForm');

    // Function to load clients via AJAX
    function loadClients(url = '{{ route("clients.index") }}') {
        const search = $('#searchInput').val();
        const status = $('#statusFilter').val();
        
        // Show skeleton
        $('#table-container').addClass('d-none');
        $('#clients-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search, status: status },
            success: function(response) {
                $('#table-container').html(response).removeClass('d-none');
                $('#clients-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load clients. Please try again.', 'error');
                $('#table-container').removeClass('d-none');
                $('#clients-skeleton').addClass('d-none');
            }
        });
    }

    // Event Listeners for Filters
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadClients(), 500);
    });

    $('#statusFilter').on('change', function() {
        loadClients();
    });

    $('#refreshBtn').on('click', function() {
        loadClients();
    });

    // Pagination Links Intercept
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadClients($(this).attr('href'));
    });

    // Open Offcanvas for Create
    window.openClientOffcanvas = function() {
        $clientForm[0].reset();
        $('#client_id').val('');
        $('#clientOffcanvasLabel').text('Add New Client');
        $('.is-invalid').removeClass('is-invalid');
        clientOffcanvas.show();
    };

    // Open Offcanvas for Edit
    $(document).on('click', '.edit-client-btn', function() {
        const client = $(this).data('client');
        $clientForm[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        
        // Populate form
        $('#client_id').val(client.id);
        $('#company_name').val(client.company_name);
        $('#email').val(client.email);
        $('#phone').val(client.phone);
        $('#website').val(client.website);
        $('#industry').val(client.industry);
        $('#gst_number').val(client.gst_number);
        $('#address').val(client.address);
        $('#city').val(client.city);
        $('#state').val(client.state);
        $('#country').val(client.country);
        $('#postal_code').val(client.postal_code);
        $('#status').val(client.status);
        
        $('#clientOffcanvasLabel').text('Edit Client');
        clientOffcanvas.show();
    });

    // Save Client (Create / Update)
    $('#saveClientBtn').on('click', function() {
        const id = $('#client_id').val();
        const url = id ? `/clients/${id}` : '{{ route("clients.store") }}';
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
            data: $clientForm.serialize(),
            success: function(response) {
                clientOffcanvas.hide();
                showToast('Success', response.message, 'success');
                loadClients();
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

    // Delete Client
    $(document).on('click', '.delete-client-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        const originalIcon = $btn.html();
        
        confirmAction('Delete Client?', 'Are you sure you want to delete this client?', function() {
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
            
            $.ajax({
                url: `/clients/${id}`,
                type: 'DELETE',
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadClients();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error deleting client', 'error');
                    $btn.html(originalIcon).prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush
