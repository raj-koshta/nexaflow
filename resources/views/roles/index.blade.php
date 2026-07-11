@extends('layouts.master')

@section('title', 'Role Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">Role Management</h1>
        <p class="text-muted">Create roles and assign permissions to them.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" onclick="openRoleModal()">
            <i class="bi bi-person-badge me-1"></i> Create Role
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Skeleton Loading -->
        @include('layouts.skeleton', ['type' => 'table', 'rows' => 5])
        
        <!-- Table Content -->
        <div class="table-responsive" id="rolesTableContainer" style="display: none;">
            <!-- Rendered via AJAX -->
        </div>
    </div>
</div>

@include('roles.modal')

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        loadRoles();

        $('#roleForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#saveRoleBtn');
            const id = $('#roleId').val();
            const url = id ? `/roles/${id}` : '{{ route("roles.store") }}';
            const method = id ? 'PUT' : 'POST';

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(res) {
                    $('#roleModal').modal('hide');
                    showToast('Success', res.message, 'success');
                    loadRoles();
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('Save Role');
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

    function loadRoles() {
        $('#rolesTableContainer').hide();
        $('.skeleton-wrapper').show();

        $.get('{{ route("roles.index") }}', function(html) {
            $('.skeleton-wrapper').hide();
            $('#rolesTableContainer').html(html).fadeIn();
            
            // Delete Handlers
            $('.delete-role-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this role?')) {
                    $.ajax({
                        url: `/roles/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            showToast('Success', res.message, 'success');
                            loadRoles();
                        },
                        error: function(xhr) {
                            showToast('Error', xhr.responseJSON?.message || 'Failed to delete role', 'error');
                        }
                    });
                }
            });

            // Edit Handlers
            $('.edit-role-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.get(`/roles/${id}/edit`, function(data) {
                    openRoleModal(data.role, data.permission_ids);
                });
            });
        });
    }

    function openRoleModal(role = null, permissionIds = []) {
        $('#roleForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#saveRoleBtn').prop('disabled', false).html('Save Role');
        $('input[name="permissions[]"]').prop('checked', false);

        if (role) {
            $('#roleModalLabel').text('Edit Role');
            $('#roleId').val(role.id);
            $('#name').val(role.name);
            if (role.name === 'Administrator') {
                $('#name').prop('readonly', true);
            } else {
                $('#name').prop('readonly', false);
            }
            // Check permissions
            permissionIds.forEach(id => {
                $(`#permission_${id}`).prop('checked', true);
            });
        } else {
            $('#roleModalLabel').text('Create Role');
            $('#roleId').val('');
            $('#name').prop('readonly', false);
        }

        $('#roleModal').modal('show');
    }
</script>
@endpush
