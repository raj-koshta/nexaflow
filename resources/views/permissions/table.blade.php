<table class="table table-hover align-middle mb-0">
    <thead class="bg-light bg-opacity-10">
        <tr>
            <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Permission Name</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Guard</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Created</th>
            <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($permissions as $permission)
            <tr>
                <td class="px-4 py-3">
                    <div class="fw-bold text-main">
                        <i class="bi bi-key text-muted me-2"></i>{{ $permission->name }}
                    </div>
                </td>
                <td class="py-3">
                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                        {{ $permission->guard_name }}
                    </span>
                </td>
                <td class="py-3 text-muted">
                    {{ $permission->created_at->format('M d, Y') }}
                </td>
                <td class="px-4 py-3 text-end">
                    <button class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 edit-permission-btn me-1" data-id="{{ $permission->id }}" title="Edit Permission">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0 delete-permission-btn" data-id="{{ $permission->id }}" title="Delete Permission">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="bi bi-shield-slash display-4 opacity-50"></i></div>
                    <h5>No Permissions Found</h5>
                    <p class="mb-0">Create permissions to assign them to roles.</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
