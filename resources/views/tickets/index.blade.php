@extends('layouts.master')

@section('title', 'Support Tickets')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Support Tickets</h1>
        <p class="text-muted mb-0">Manage customer inquiries, bugs, and feature requests.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" onclick="openTicketOffcanvas()">
            <i class="bi bi-plus-lg me-1"></i> New Ticket
        </button>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
            <div class="position-relative" style="max-width: 350px; width: 100%;">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" class="form-control ps-5" id="searchInput" placeholder="Search subject or ticket ID...">
            </div>
            <div class="d-flex gap-2">
                <select class="form-select" id="statusFilter" style="min-width: 140px;">
                    <option value="">All Statuses</option>
                    <option value="Open">Open</option>
                    <option value="Pending">Pending</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                </select>
                <select class="form-select" id="priorityFilter" style="min-width: 140px;">
                    <option value="">All Priorities</option>
                    <option value="Urgent">Urgent</option>
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
            @include('tickets.partials.table')
        </div>
        
        <!-- Skeleton Loader (Hidden by Default) -->
        <div id="tickets-skeleton" class="d-none">
            @include('tickets.skeleton')
        </div>
    </div>
</div>

@push('modals')
    @include('tickets.form')
    
    <!-- Quick View Modal -->
    <div class="modal fade" id="ticketQuickViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg" style="border: var(--glass-border) !important; border-radius: 16px; overflow: hidden;">
                <div id="quickViewContent">
                    <!-- Content loaded via AJAX -->
                    <div class="p-5 text-center text-muted">
                        <div class="spinner-border mb-3" role="status"></div>
                        <div>Loading ticket details...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@endsection

@push('custom-scripts')
<script>
    let searchTimeout = null;
    const ticketOffcanvas = new bootstrap.Offcanvas(document.getElementById('ticketOffcanvas'));
    const $ticketForm = $('#ticketForm');

    function loadTickets(url = '{{ route("tickets.index") }}') {
        const search = $('#searchInput').val();
        const status = $('#statusFilter').val();
        const priority = $('#priorityFilter').val();
        
        $('#table-container').addClass('d-none');
        $('#tickets-skeleton').removeClass('d-none');

        $.ajax({
            url: url,
            type: 'GET',
            data: { search: search, status: status, priority: priority },
            success: function(response) {
                $('#table-container').html(response).removeClass('d-none');
                $('#tickets-skeleton').addClass('d-none');
            },
            error: function() {
                showToast('Error', 'Failed to load tickets.', 'error');
                $('#table-container').removeClass('d-none');
                $('#tickets-skeleton').addClass('d-none');
            }
        });
    }

    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => loadTickets(), 500);
    });

    $('#statusFilter, #priorityFilter').on('change', function() {
        loadTickets();
    });

    $('#refreshBtn').on('click', function() {
        loadTickets();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        loadTickets($(this).attr('href'));
    });

    window.openTicketOffcanvas = function(ticket = null) {
        $ticketForm[0].reset();
        $ticketForm.find('.is-invalid').removeClass('is-invalid');
        
        if (ticket) {
            $('#ticketOffcanvasLabel').text('Edit Ticket ' + ticket.ticket_number);
            $('#ticket_id').val(ticket.id);
            $('#subject').val(ticket.subject);
            $('#client_id').val(ticket.client_id);
            $('#assigned_to').val(ticket.assigned_to);
            $('#status').val(ticket.status);
            $('#priority').val(ticket.priority);
            $('#category').val(ticket.category);
            $('#description').val(ticket.description);
        } else {
            $('#ticketOffcanvasLabel').text('Create Ticket');
            $('#ticket_id').val('');
            $('#status').val('Open');
            $('#priority').val('Medium');
            $('#category').val('Support');
        }
        
        ticketOffcanvas.show();
    };

    $(document).on('click', '.edit-ticket-btn', function(e) {
        e.preventDefault();
        openTicketOffcanvas($(this).data('ticket'));
    });

    // Quick View Ticket
    $(document).on('click', '.quick-view-btn', function(e) {
        e.preventDefault();
        const url = $(this).data('url');
        
        $('#quickViewContent').html(`
            <div class="p-5 text-center text-muted">
                <div class="spinner-border mb-3" role="status"></div>
                <div>Loading ticket details...</div>
            </div>
        `);
        
        const modal = new bootstrap.Modal(document.getElementById('ticketQuickViewModal'));
        modal.show();

        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#quickViewContent').html(response);
            },
            error: function() {
                $('#quickViewContent').html(`
                    <div class="p-5 text-center text-danger">
                        <i class="bi bi-exclamation-triangle fs-1 d-block mb-3"></i>
                        <h5>Error loading ticket details</h5>
                        <p class="text-muted">Please try again or open the full ticket.</p>
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                `);
            }
        });
    });

    $ticketForm.on('submit', function(e) {
        e.preventDefault();
        
        const id = $('#ticket_id').val();
        const url = id ? `/tickets/${id}` : '{{ route("tickets.store") }}';
        const method = id ? 'PUT' : 'POST';
        
        $('#form-loading').removeClass('d-none').addClass('d-flex');
        $ticketForm.find('.is-invalid').removeClass('is-invalid');

        $.ajax({
            url: url,
            type: method,
            data: $(this).serialize(),
            success: function(response) {
                showToast('Success', response.message, 'success');
                ticketOffcanvas.hide();
                loadTickets();
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

    $(document).on('click', '.delete-ticket-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        confirmAction('Delete Ticket?', 'Are you sure you want to delete this ticket? This will soft-delete the record.', function() {
            $.ajax({
                url: `/tickets/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    loadTickets();
                },
                error: function(xhr) {
                    showToast('Error', 'Failed to delete ticket.', 'error');
                }
            });
        });
    });
</script>
@endpush
