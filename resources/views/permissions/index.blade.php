@extends('layouts.master')

@section('title', 'Permissions Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">Permissions Management</h1>
        <p class="text-muted">Define granular access rights for the system.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" onclick="openPermissionModal()">
            <i class="bi bi-shield-lock me-1"></i> Create Permission
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Skeleton Loading -->
        @include('layouts.skeleton', ['type' => 'table', 'rows' => 5])
        
        <!-- Table Content -->
        <div class="table-responsive" id="permissionsTableContainer" style="display: none;">
            <!-- Rendered via AJAX -->
        </div>
    </div>
</div>

@include('permissions.modal')

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        loadPermissions();

        $('#permissionForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#savePermissionBtn');
            const id = $('#permissionId').val();
            const url = id ? `/permissions/${id}` : '{{ route("permissions.store") }}';
            const method = id ? 'PUT' : 'POST';

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(res) {
                    $('#permissionModal').modal('hide');
                    showToast('Success', res.message, 'success');
                    loadPermissions();
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('Save Permission');
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}_err`).text(errors[field][0]);
                        }
                    } else {
                        showToast('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                    }
                }
            });
        });
    });

    function loadPermissions() {
        $('#permissionsTableContainer').hide();
        $('.skeleton-wrapper').show();

        $.get('{{ route("permissions.index") }}', function(html) {
            $('.skeleton-wrapper').hide();
            $('#permissionsTableContainer').html(html).fadeIn();
            
            // Delete Handlers
            $('.delete-permission-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this permission?')) {
                    $.ajax({
                        url: `/permissions/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            showToast('Success', res.message, 'success');
                            loadPermissions();
                        },
                        error: function(xhr) {
                            showToast('Error', xhr.responseJSON?.message || 'Failed to delete permission', 'error');
                        }
                    });
                }
            });

            // Edit Handlers
            $('.edit-permission-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.get(`/permissions/${id}/edit`, function(permission) {
                    openPermissionModal(permission);
                });
            });
        });
    }

    function openPermissionModal(permission = null) {
        $('#permissionForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#savePermissionBtn').prop('disabled', false).html('Save Permission');

        if (permission) {
            $('#permissionModalLabel').text('Edit Permission');
            $('#permissionId').val(permission.id);
            $('#name').val(permission.name);
        } else {
            $('#permissionModalLabel').text('Create Permission');
            $('#permissionId').val('');
        }

        $('#permissionModal').modal('show');
    }
</script>
@endpush
