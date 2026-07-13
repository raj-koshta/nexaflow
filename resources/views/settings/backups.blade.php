@extends('layouts.master')

@section('title', 'Backup Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('settings.index') }}" class="text-decoration-none">Settings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Backups</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">System Backups</h1>
        <p class="text-muted">Manage manual and automated backups of your database and storage files.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary shadow-sm" id="runBackupBtn">
            <span class="indicator-label"><i class="bi bi-cloud-arrow-up me-1"></i> Run New Backup</span>
            <span class="indicator-progress d-none">
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Backing up...
            </span>
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border) !important;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-10">
                    <tr>
                        <th class="ps-4 border-0">Backup File</th>
                        <th class="border-0">Size</th>
                        <th class="border-0">Date Created</th>
                        <th class="text-end pe-4 border-0">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($backups as $backup)
                    <tr class="backup-row" data-file="{{ $backup['file_name'] }}">
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-file-earmark-zip-fill fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-main">{{ $backup['file_name'] }}</h6>
                                    <small class="text-muted">Stored locally</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">{{ $backup['file_size'] }}</span></td>
                        <td>
                            <div class="text-main fw-medium">{{ $backup['created_at_human'] }}</div>
                            <small class="text-muted">{{ $backup['created_at'] }}</small>
                        </td>
                        <td class="text-end pe-4">
                            <a href="{{ route('backups.download', ['file_name' => $backup['file_name']]) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Download">
                                <i class="bi bi-download"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger rounded-circle delete-backup-btn" data-file="{{ $backup['file_name'] }}" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">
                            <i class="bi bi-hdd-network fs-1 opacity-50 mb-3 d-block"></i>
                            <p class="mb-0">No backups found. Run a new backup to safeguard your data.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $('#runBackupBtn').off('click').on('click', function() {
        const $btn = $(this);
        const $label = $btn.find('.indicator-label');
        const $progress = $btn.find('.indicator-progress');
        
        $btn.prop('disabled', true);
        $label.addClass('d-none');
        $progress.removeClass('d-none');
        
        showToast('Info', 'Backup process started. This may take a minute...', 'info');

        $.ajax({
            url: '{{ route("backups.store") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                showToast('Success', response.message, 'success');
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error running backup.', 'error');
                $btn.prop('disabled', false);
                $label.removeClass('d-none');
                $progress.addClass('d-none');
            }
        });
    });

    $('.delete-backup-btn').off('click').on('click', function() {
        const fileName = $(this).data('file');
        const $row = $(this).closest('tr');

        confirmAction('Delete Backup?', 'Are you sure you want to permanently delete this backup file?', function() {
            $.ajax({
                url: '{{ route("backups.destroy") }}',
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    file_name: fileName
                },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    $row.fadeOut(300, function() { $(this).remove(); });
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Error deleting backup.', 'error');
                }
            });
        });
    });
</script>
@endpush