@extends('layouts.master')

@section('title', 'Manage Team - ' . $team->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('teams.index') }}" class="text-decoration-none">Teams</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $team->name }}</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">{{ $team->name }}</h1>
        <p class="text-muted">{{ $team->description ?: 'No description provided.' }}</p>
    </div>
</div>

<div class="row">
    <!-- Add Member Form -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Add Member</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('teams.members.add', $team->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Select User</label>
                        <select class="form-select" name="user_id" required>
                            <option value="">-- Choose User --</option>
                            @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        @if($availableUsers->isEmpty())
                            <div class="form-text text-warning"><i class="bi bi-exclamation-triangle me-1"></i>All system users are already in this team.</div>
                        @endif
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="is_leader" name="is_leader" value="1">
                        <label class="form-check-label" for="is_leader">Assign as Team Leader</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" {{ $availableUsers->isEmpty() ? 'disabled' : '' }}>
                        Add to Team
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Members List -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom pt-3 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Team Members ({{ $team->users->count() }})</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-10">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">User</th>
                                <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Role in Team</th>
                                <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Joined Team</th>
                                <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($team->users as $member)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=8b5cf6&color=fff&size=36" class="rounded-circle me-3" alt="{{ $member->name }}">
                                            <div>
                                                <div class="fw-bold text-main">{{ $member->name }}</div>
                                                <div class="text-muted small">{{ $member->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        @if($member->pivot->is_leader)
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                                <i class="bi bi-star-fill me-1"></i> Leader
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                                <i class="bi bi-person me-1"></i> Member
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted">
                                        {{ $member->pivot->created_at ? $member->pivot->created_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <form action="{{ route('teams.members.remove', [$team->id, $member->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0" title="Remove Member" onclick="return confirm('Are you sure you want to remove this user from the team?')">
                                                <i class="bi bi-x-circle"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="mb-3"><i class="bi bi-people display-4 opacity-50"></i></div>
                                        <h5>No Members Yet</h5>
                                        <p class="mb-0">Use the form to add users to this team.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
