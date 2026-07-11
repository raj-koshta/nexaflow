<table class="table table-hover align-middle mb-0">
    <thead class="bg-light bg-opacity-10">
        <tr>
            <th class="px-4 py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Company</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Contact Info</th>
            <th class="py-3 text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Members</th>
            <th class="px-4 py-3 text-end text-uppercase text-muted small fw-semibold" style="letter-spacing: 0.5px;">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($companies as $company)
            <tr>
                <td class="px-4 py-3">
                    <div class="d-flex align-items-center">
                        @if($company->logo_path)
                            <img src="{{ asset('storage/' . $company->logo_path) }}" class="rounded me-3 object-fit-cover" width="40" height="40" alt="{{ $company->name }}">
                        @else
                            <div class="rounded me-3 d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fw-bold" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                {{ strtoupper(substr($company->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="fw-bold text-main">{{ $company->name }}</div>
                            <div class="text-muted small">ID: #{{ str_pad($company->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </div>
                    </div>
                </td>
                <td class="py-3">
                    @if($company->email)
                        <div class="text-main small"><i class="bi bi-envelope text-muted me-1"></i> {{ $company->email }}</div>
                    @endif
                    @if($company->phone)
                        <div class="text-main small"><i class="bi bi-telephone text-muted me-1"></i> {{ $company->phone }}</div>
                    @endif
                    @if(!$company->email && !$company->phone)
                        <span class="text-muted small">No contact info</span>
                    @endif
                </td>
                <td class="py-3">
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3">
                        <i class="bi bi-people-fill me-1"></i> {{ $company->users_count }} Users
                    </span>
                </td>
                <td class="px-4 py-3 text-end">
                    <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 me-1" title="Manage Company">
                        <i class="bi bi-gear"></i> Manage
                    </a>
                    <button class="btn btn-sm bg-primary bg-opacity-10 text-primary border-0 edit-company-btn me-1" data-id="{{ $company->id }}" title="Edit Company">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm bg-danger bg-opacity-10 text-danger border-0 delete-company-btn" data-id="{{ $company->id }}" title="Delete Company">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-muted">
                    <div class="mb-3"><i class="bi bi-building display-4 opacity-50"></i></div>
                    <h5>No Companies Found</h5>
                    <p class="mb-0">Create a company to manage clients or branches.</p>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
