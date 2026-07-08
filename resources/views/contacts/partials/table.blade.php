@if($contacts->count() > 0)
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Contact Details</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Client</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Role / Dept</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="border-bottom-0 text-end text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-info bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: #0dcaf0;">
                                        <i class="bi bi-person-circle fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $contact->name }}</h6>
                                        <small class="text-muted"><i class="bi bi-envelope me-1"></i>{{ $contact->email ?? 'No email' }}</small>
                                        @if($contact->phone || $contact->mobile)
                                            <br><small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $contact->mobile ?? $contact->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($contact->client)
                                    <span class="fw-medium text-primary">{{ $contact->client->company_name }}</span>
                                @else
                                    <span class="text-muted fst-italic">No Client</span>
                                @endif
                            </td>
                            <td>
                                <span class="d-block fw-medium">{{ $contact->designation ?? 'N/A' }}</span>
                                <small class="text-muted">{{ $contact->department ?? '-' }}</small>
                            </td>
                            <td>
                                @if($contact->is_primary)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill px-3">
                                        <i class="bi bi-star-fill me-1"></i>Primary
                                    </span>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-0 set-primary-btn" data-id="{{ $contact->id }}" style="font-size: 0.75rem;">
                                        Set Primary
                                    </button>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-link text-muted edit-contact-btn p-1" data-contact="{{ json_encode($contact) }}" title="Edit Contact">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-contact-btn p-1" data-id="{{ $contact->id }}" title="Delete Contact">
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
                    Showing {{ $contacts->firstItem() ?? 0 }} to {{ $contacts->lastItem() ?? 0 }} of {{ $contacts->total() }} entries
                </div>
                <div>
                    {{ $contacts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                <i class="bi bi-person-vcard opacity-50"></i>
            </div>
            <h4 class="fw-bold">No contacts found</h4>
            <p class="text-muted mb-4">You haven't added any contacts yet, or your search didn't match any records.</p>
            <button class="btn btn-primary px-4" data-bs-toggle="offcanvas" data-bs-target="#contactOffcanvas">
                <i class="bi bi-plus-lg me-2"></i>Add First Contact
            </button>
        </div>
    </div>
@endif
