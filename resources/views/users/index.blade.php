@extends('layouts.master')

@section('title', 'User Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">User Management</h1>
        <p class="text-muted">Manage system users, roles, and access.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" onclick="openUserModal()">
            <i class="bi bi-person-plus me-1"></i> Add User
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Skeleton Loading -->
        @include('layouts.skeleton', ['type' => 'table', 'rows' => 5])
        
        <!-- Table Content -->
        <div class="table-responsive" id="usersTableContainer" style="display: none;">
            <!-- Rendered via AJAX -->
        </div>
    </div>
</div>

@include('users.modal')

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        loadUsers();

        $('#userForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#saveUserBtn');
            const id = $('#userId').val();
            const url = id ? `/users/${id}` : '{{ route("users.store") }}';
            const method = id ? 'PUT' : 'POST';

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(res) {
                    $('#userModal').modal('hide');
                    showToast('Success', res.message, 'success');
                    loadUsers();
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('Save User');
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

    function loadUsers() {
        $('#usersTableContainer').hide();
        $('.skeleton-wrapper').show();

        $.get('{{ route("users.index") }}', function(html) {
            $('.skeleton-wrapper').hide();
            $('#usersTableContainer').html(html).fadeIn();
            
            // Delete Handlers
            $('.delete-user-btn').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('Are you sure you want to remove this user?')) {
                    $.ajax({
                        url: `/users/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            showToast('Success', res.message, 'success');
                            loadUsers();
                        },
                        error: function(xhr) {
                            showToast('Error', xhr.responseJSON?.message || 'Failed to delete user', 'error');
                        }
                    });
                }
            });

            // Edit Handlers
            $('.edit-user-btn').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.get(`/users/${id}/edit`, function(user) {
                    openUserModal(user);
                });
            });
        });
    }

    function openUserModal(data = null) {
        $('#userForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#saveUserBtn').prop('disabled', false).html('Save User');
        $('input[name="roles[]"]').prop('checked', false);

        if (data) {
            $('#userModalLabel').text('Edit User');
            $('#userId').val(data.user.id);
            $('#name').val(data.user.name);
            $('#email').val(data.user.email);
            
            if (data.roles) {
                data.roles.forEach(role => {
                    $(`input[name="roles[]"][value="${role}"]`).prop('checked', true);
                });
            }
            $('#passwordSectionHelp').text('Leave blank to keep current password.');
        } else {
            $('#userModalLabel').text('Add User');
            $('#userId').val('');
            $('#passwordSectionHelp').text('');
        }

        $('#userModal').modal('show');
    }
</script>
@endpush
