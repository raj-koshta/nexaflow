@if($leads->count() > 0)
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Lead / Contact</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Company</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Status</th>
                            <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Priority</th>
                            <th class="border-bottom-0 text-end text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leads as $lead)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; color: var(--accent);">
                                        <i class="bi bi-person-bounding-box fs-5"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $lead->name }}</h6>
                                        <small class="text-muted">{{ $lead->email ?? $lead->phone ?? 'No contact info' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($lead->company)
                                    <span class="d-block fw-medium">{{ $lead->company }}</span>
                                @else
                                    <span class="text-muted fst-italic">Individual</span>
                                @endif
                                <small class="text-muted"><i class="bi bi-box-arrow-in-right me-1"></i>{{ ucfirst(str_replace('_', ' ', $lead->source ?? 'Unknown')) }}</small>
                            </td>
                            <td>
                                @php
                                    $statusColors = [
                                        'new' => 'primary',
                                        'contacted' => 'info',
                                        'qualified' => 'success',
                                        'lost' => 'secondary'
                                    ];
                                    $color = $statusColors[$lead->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 rounded-pill px-3">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                            <td>
                                @php
                                    $priorityColors = [
                                        'low' => 'secondary',
                                        'medium' => 'warning',
                                        'high' => 'danger'
                                    ];
                                    $pColor = $priorityColors[$lead->priority] ?? 'secondary';
                                @endphp
                                <span class="badge" style="background: rgba(255,255,255,0.05); color: var(--text-main); font-weight: normal; border: 1px solid var(--border-color);">
                                    <i class="bi bi-circle-fill me-1 text-{{ $pColor }}" style="font-size: 0.5rem;"></i> {{ ucfirst($lead->priority) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-link text-muted edit-lead-btn p-1" data-lead="{{ json_encode($lead) }}" title="Edit Lead">
                                    <i class="bi bi-pencil-square fs-5"></i>
                                </button>
                                <button class="btn btn-sm btn-link text-danger delete-lead-btn p-1" data-id="{{ $lead->id }}" title="Delete Lead">
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
                    Showing {{ $leads->firstItem() ?? 0 }} to {{ $leads->lastItem() ?? 0 }} of {{ $leads->total() }} entries
                </div>
                <div>
                    {{ $leads->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@else
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body text-center py-5">
            <div class="mb-4 text-muted" style="font-size: 4rem;">
                <i class="bi bi-person-lines-fill opacity-50"></i>
            </div>
            <h4 class="fw-bold">No leads found</h4>
            <p class="text-muted mb-4">You haven't added any leads yet, or your search didn't match any records.</p>
            <button class="btn btn-primary px-4" data-bs-toggle="offcanvas" data-bs-target="#leadOffcanvas">
                <i class="bi bi-plus-lg me-2"></i>Add First Lead
            </button>
        </div>
    </div>
@endif
