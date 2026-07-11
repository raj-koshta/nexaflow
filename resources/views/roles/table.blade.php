<table class="table table-hover align-middle mb-0">
    <thead class="bg-light bg-opacity-10">
        <tr>
            <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Role Name</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Permissions</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Users</th>
            <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($roles as $role)
            <tr>
                <td class="px-4 py-3">
                    <div class="fw-bold text-main">{{ $role->name }}</div>
                </td>
                <td class="py-3">
                    @if($role->name === 'Administrator')
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">All Permissions</span>
                    @else
                        @if($role->permissions->count() > 0)
                            <div class="d-flex flex-wrap gap-1" style="max-width: 300px;">
                                @foreach($role->permissions->take(3) as $perm)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10">{{ $perm->name }}</span>
                                @endforeach
                                @if($role->permissions->count() > 3)
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-10">+{{ $role->permissions->count() - 3 }} more</span>
                                @endif
                            </div>
                        @else
                            <span class="text-muted small fst-italic">No permissions assigned</span>
                        @endif
                    @endif
                </td>
                <td class="py-3">
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                        <i class="bi bi-people-fill me-1"></i> {{ $role->users_count }}
                    </span>
                </td>
                <td class="px-4 py-3 text-end">
                    <button class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 edit-role-btn me-1" data-id="{{ $role->id }}" title="Edit Role">
                        <i class="bi bi-pencil"></i>
                    </button>
                    @if($role->name !== 'Administrator')
                        <button class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0 delete-role-btn" data-id="{{ $role->id }}" title="Delete Role">
                            <i class="bi bi-trash"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="bi bi-person-badge display-4 opacity-50"></i></div>
                    <h5>No Roles Found</h5>
                    <p class="mb-0">Create roles to start defining access levels.</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
