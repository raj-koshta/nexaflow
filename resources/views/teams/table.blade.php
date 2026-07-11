<table class="table table-hover align-middle mb-0">
    <thead class="bg-light bg-opacity-10">
        <tr>
            <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Team Name</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Members</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Created</th>
            <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($teams as $team)
            <tr>
                <td class="px-4 py-3">
                    <div class="fw-bold text-main">{{ $team->name }}</div>
                    <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $team->description ?: 'No description provided' }}</div>
                </td>
                <td class="py-3">
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                        <i class="bi bi-people-fill me-1"></i> {{ $team->users_count }}
                    </span>
                </td>
                <td class="py-3 text-muted">
                    {{ $team->created_at->format('M d, Y') }}
                </td>
                <td class="px-4 py-3 text-end">
                    <a href="{{ route('teams.show', $team->id) }}" class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 me-1" title="Manage Members">
                        <i class="bi bi-gear"></i> Manage
                    </a>
                    <button class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 edit-team-btn me-1" data-id="{{ $team->id }}" title="Edit Team">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0 delete-team-btn" data-id="{{ $team->id }}" title="Delete Team">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="bi bi-diagram-3 display-4 opacity-50"></i></div>
                    <h5>No Teams Found</h5>
                    <p class="mb-0">Create a team to start grouping your users.</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
