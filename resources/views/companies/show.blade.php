@extends('layouts.master')

@section('title', 'Manage Company - ' . $company->name)

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div class="d-flex align-items-center">
        @if($company->logo_path)
            <img src="{{ asset('storage/' . $company->logo_path) }}" class="rounded me-3 object-fit-cover shadow-sm border" width="60" height="60" alt="{{ $company->name }}">
        @else
            <div class="rounded me-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold shadow-sm" style="width: 60px; height: 60px; font-size: 1.5rem;">
                {{ strtoupper(substr($company->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('companies.index') }}" class="text-decoration-none">Companies</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $company->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0 text-main">{{ $company->name }}</h1>
        </div>
    </div>
</div>

<div class="row">
    <!-- Add Member Form & Details -->
    <div class="col-lg-4 mb-4">
        <!-- Details Card -->
        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-info-circle me-2 text-primary"></i>Company Details</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3">
                        <div class="text-muted small fw-medium">Email</div>
                        <div class="text-main">{{ $company->email ?: 'N/A' }}</div>
                    </li>
                    <li class="mb-3">
                        <div class="text-muted small fw-medium">Phone</div>
                        <div class="text-main">{{ $company->phone ?: 'N/A' }}</div>
                    </li>
                    <li class="mb-3">
                        <div class="text-muted small fw-medium">Website</div>
                        @if($company->website)
                            <a href="{{ $company->website }}" target="_blank" class="text-decoration-none">{{ $company->website }} <i class="bi bi-box-arrow-up-right ms-1 small"></i></a>
                        @else
                            <div class="text-main">N/A</div>
                        @endif
                    </li>
                    <li>
                        <div class="text-muted small fw-medium">Address</div>
                        <div class="text-main">{{ $company->address ?: 'N/A' }}</div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Add Member Card -->
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-plus me-2 text-primary"></i>Add User to Company</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('companies.members.add', $company->id) }}" method="POST">
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
                            <div class="form-text text-warning"><i class="bi bi-exclamation-triangle me-1"></i>All system users are already in this company.</div>
                        @endif
                    </div>
                    
                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="is_primary" name="is_primary" value="1">
                        <label class="form-check-label" for="is_primary">Set as Primary Contact</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100" {{ $availableUsers->isEmpty() ? 'disabled' : '' }}>
                        Add to Company
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Members List -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom pt-3 pb-2 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Company Users ({{ $company->users->count() }})</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-10">
                            <tr>
                                <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">User</th>
                                <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Contact Status</th>
                                <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Added Date</th>
                                <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($company->users as $member)
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
                                        @if($member->pivot->is_primary)
                                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3">
                                                <i class="bi bi-star-fill me-1"></i> Primary Contact
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3">
                                                <i class="bi bi-person me-1"></i> Standard
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-muted">
                                        {{ $member->pivot->created_at ? $member->pivot->created_at->format('M d, Y') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <form action="{{ route('companies.members.remove', [$company->id, $member->id]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0" title="Remove User" onclick="return confirm('Are you sure you want to remove this user from the company?')">
                                                <i class="bi bi-x-circle"></i> Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        <div class="mb-3"><i class="bi bi-people display-4 opacity-50"></i></div>
                                        <h5>No Users Found</h5>
                                        <p class="mb-0">Add users to this company to grant them access or track contacts.</p>
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
