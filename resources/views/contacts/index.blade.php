@extends('layouts.master')

@section('title', 'Contacts')

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
        <h1 class="h2 fw-bold mb-0">Contacts</h1>
        <p class="text-muted mb-0">Manage key people across your clients.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openContactOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> Add Contact
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div class="position-relative" style="max-width: 350px; width: 100%;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5" id="searchInput" placeholder="Search contacts by name, email, phone...">
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" id="clientFilter" style="min-width: 200px;">
                    <option value="">All Clients</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                </select>
                <button class="btn btn-outline-secondary d-flex align-items-center" id="refreshBtn" title="Refresh">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>
        </div>

        <div id="table-container">
            @include('contacts.partials.table')
        </div>
        
        @include('contacts.skeleton')
    </div>
</div>

@include('contacts.form')

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    const contactOffcanvas = new bootstrap.Offcanvas(document.getElementById('contactOffcanvas'));
    const $contactForm = $('#contactForm');

    // Function to load contacts via AJAX
    function loadContacts(url = '{{ route("contacts.index") }}') {
        const search = $('#searchInput').val();
        const client_id = $('#clientFilter').val();
        
        // Show skeleton
        $('#table-container').addClass('d-none');
        $('#contacts-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search, client_id: client_id },
            success: function(response) {
                $('#table-container').html(response).removeClass('d-none');
                $('#contacts-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load contacts. Please try again.', 'error');
                $('#table-container').removeClass('d-none');
                $('#contacts-skeleton').addClass('d-none');
            }
        });
    }

    // Event Listeners for Filters
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadContacts(), 500);
    });

    $('#clientFilter').on('change', function() {
        loadContacts();
    });

    $('#refreshBtn').on('click', function() {
        loadContacts();
    });

    // Pagination Links Intercept
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadContacts($(this).attr('href'));
    });

    // Open Offcanvas for Create
    window.openContactOffcanvas = function() {
        $contactForm[0].reset();
        $('#contact_id').val('');
        $('#is_primary').prop('checked', false);
        $('#contactOffcanvasLabel').text('Add New Contact');
        $('.is-invalid').removeClass('is-invalid');
        contactOffcanvas.show();
    };

    // Open Offcanvas for Edit
    $(document).on('click', '.edit-contact-btn', function() {
        const contact = $(this).data('contact');
        $contactForm[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        
        // Populate form
        $('#contact_id').val(contact.id);
        $('#client_id').val(contact.client_id);
        $('#name').val(contact.name);
        $('#designation').val(contact.designation);
        $('#department').val(contact.department);
        $('#email').val(contact.email);
        $('#phone').val(contact.phone);
        $('#mobile').val(contact.mobile);
        $('#is_primary').prop('checked', contact.is_primary);
        
        $('#contactOffcanvasLabel').text('Edit Contact');
        contactOffcanvas.show();
    });

    // Save Contact (Create / Update)
    $('#saveContactBtn').on('click', function() {
        const id = $('#contact_id').val();
        const url = id ? `/contacts/${id}` : '{{ route("contacts.store") }}';
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
            data: $contactForm.serialize(),
            success: function(response) {
                contactOffcanvas.hide();
                showToast('Success', response.message, 'success');
                loadContacts();
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

    // Delete Contact
    $(document).on('click', '.delete-contact-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        const originalIcon = $btn.html();
        
        confirmAction('Delete Contact?', 'Are you sure you want to delete this contact?', function() {
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
            
            $.ajax({
                url: `/contacts/${id}`,
                type: 'DELETE',
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadContacts();
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error deleting contact', 'error');
                    $btn.html(originalIcon).prop('disabled', false);
                }
            });
        });
    });

    // Set Primary Contact
    $(document).on('click', '.set-primary-btn', function() {
        const id = $(this).data('id');
        const $btn = $(this);
        const originalText = $btn.text();
        
        $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>').prop('disabled', true);
        
        $.ajax({
            url: `/contacts/${id}/primary`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                showToast('Success', response.message, 'success');
                loadContacts();
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error updating primary status', 'error');
                $btn.text(originalText).prop('disabled', false);
            }
        });
    });
</script>
@endpush
