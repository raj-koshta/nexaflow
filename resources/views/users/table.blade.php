<table class="table table-hover align-middle mb-0">
    <thead class="bg-light">
        <tr>
            <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">User</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Role</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Status</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Joined</th>
            <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $user)
            <tr>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=8b5cf6&color=fff&size=40" class="rounded-circle me-3" alt="{{ $user->name }}">
                        <div>
                            <div class="fw-bold">{{ $user->name }}</div>
                            <div class="text-muted small">{{ $user->email }}</div>
                        </div>
                    </div>
                </td>
                <td class="py-3">
                    @if($user->roles->count() > 0)
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($user->roles as $role)
                                @if($role->name === 'Administrator')
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3"><i class="bi bi-shield-lock-fill me-1"></i> Admin</span>
                                @else
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">{{ $role->name }}</span>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">User</span>
                    @endif
                </td>
                <td class="py-3">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2">Active</span>
                </td>
                <td class="py-3 text-muted">
                    {{ $user->created_at->format('M d, Y') }}
                </td>
                <td class="px-4 py-3 text-end">
                    <button class="btn btn-sm btn-light text-primary edit-user-btn me-1" data-id="{{ $user->id }}" title="Edit User">
                        <i class="bi bi-pencil"></i>
                    </button>
                    @if(auth()->id() !== $user->id)
                    <button class="btn btn-sm btn-light text-danger delete-user-btn" data-id="{{ $user->id }}" title="Delete User">
                        <i class="bi bi-trash"></i>
                    </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="bi bi-people display-4 opacity-50"></i></div>
                    <h5>No Users Found</h5>
                    <p class="mb-0">You are the only user in the system.</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
