@if($clients->count() > 0)
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Company</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Contact</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Industry</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="border-bottom-0 text-end text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($clients as $client)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--accent);">
                                        <i class="bi bi-building fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $client->company_name }}</h6>
                                        <small class="text-muted">{{ $client->client_code }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div><i class="bi bi-envelope me-2 text-muted"></i>{{ $client->email ?? 'N/A' }}</div>
                                <div><i class="bi bi-telephone me-2 text-muted"></i>{{ $client->phone ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background: rgba(255,255,255,0.1); color: var(--text-main); font-weight: normal;">
                                    {{ $client->industry ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                @if($client->status === 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">Active</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill px-3">Inactive</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('clients.show', $client->id) }}" class="btn btn-sm btn-link text-primary p-1" title="View Profile">
                                    <i class="bi bi-eye fs-5"></i>
                                </a>
                                <button class="btn btn-sm btn-link text-muted edit-client-btn p-1" data-client="{{ json_encode($client) }}" title="Edit Client">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-client-btn p-1" data-id="{{ $client->id }}" title="Delete Client">
                                    <i class="bi bi-trash fs-5"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-between align-items-center p-3 border-top" style="border-color: var(--border-color) !important;">
                <div class="text-muted small">
                    Showing {{ $clients->firstItem() ?? 0 }} to {{ $clients->lastItem() ?? 0 }} of {{ $clients->total() }} entries
                </div>
                <div>
                    {{ $clients->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                <i class="bi bi-emoji-frown"></i>
            </div>
            <h4 class="fw-bold">No clients found</h4>
            <p class="text-muted mb-4">You haven't added any clients yet, or your search didn't match any records.</p>
            <button class="btn btn-primary px-4" data-bs-toggle="offcanvas" data-bs-target="#clientOffcanvas">
                <i class="bi bi-plus-lg me-2"></i>Add First Client
            </button>
        </div>
    </div>
@endif
