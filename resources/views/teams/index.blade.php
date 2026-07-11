@extends('layouts.master')

@section('title', 'Team Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">Team Management</h1>
        <p class="text-muted">Group users into functional teams and departments.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" onclick="openTeamModal()">
            <i class="bi bi-diagram-3 me-1"></i> Create Team
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Skeleton Loading -->
        @include('layouts.skeleton', ['type' => 'table', 'rows' => 5])
        
        <!-- Table Content -->
        <div class="table-responsive" id="teamsTableContainer" style="display: none;">
            <!-- Rendered via AJAX -->
        </div>
    </div>
</div>

@include('teams.modal')

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        loadTeams();

        $('#teamForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#saveTeamBtn');
            const id = $('#teamId').val();
            const url = id ? `/teams/${id}` : '{{ route("teams.store") }}';
            const method = id ? 'PUT' : 'POST';

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: method,
                data: $(this).serialize(),
                success: function(res) {
                    $('#teamModal').modal('hide');
                    showToast('Success', res.message, 'success');
                    loadTeams();
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('Save Team');
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

    function loadTeams() {
        $('#teamsTableContainer').hide();
        $('.skeleton-wrapper').show();

        $.get('{{ route("teams.index") }}', function(html) {
            $('.skeleton-wrapper').hide();
            $('#teamsTableContainer').html(html).fadeIn();
            
            // Delete Handlers
            $('.delete-team-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this team?')) {
                    $.ajax({
                        url: `/teams/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            showToast('Success', res.message, 'success');
                            loadTeams();
                        },
                        error: function(xhr) {
                            showToast('Error', xhr.responseJSON?.message || 'Failed to delete team', 'error');
                        }
                    });
                }
            });

            // Edit Handlers
            $('.edit-team-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.get(`/teams/${id}/edit`, function(team) {
                    openTeamModal(team);
                });
            });
        });
    }

    function openTeamModal(team = null) {
        $('#teamForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#saveTeamBtn').prop('disabled', false).html('Save Team');

        if (team) {
            $('#teamModalLabel').text('Edit Team');
            $('#teamId').val(team.id);
            $('#name').val(team.name);
            $('#description').val(team.description);
        } else {
            $('#teamModalLabel').text('Create Team');
            $('#teamId').val('');
        }

        $('#teamModal').modal('show');
    }
</script>
@endpush
