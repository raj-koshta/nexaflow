<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Documents</h5>
    </div>

    <!-- Upload Document Form -->
    <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body">
            <form id="uploadDocForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="d-flex gap-2">
                    <input type="file" class="form-control" name="file" required accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip">
                    <button type="submit" class="btn btn-primary px-4 text-nowrap">
                        <span class="indicator-label"><i class="bi bi-upload me-2"></i>Upload</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
                <small class="text-muted mt-2 d-block">Allowed: PDF, DOC, XLS, JPG, PNG, ZIP. Max size: 10MB.</small>
            </form>
        </div>
    </div>

    <!-- Documents List -->
    <div class="row g-3" id="documentsList">
        @forelse($client->documents->sortByDesc('created_at') as $document)
            @php
                $ext = pathinfo($document->file_name, PATHINFO_EXTENSION);
                $icon = 'bi-file-earmark';
                $color = 'secondary';
                
                if(in_array($ext, ['pdf'])) { $icon = 'bi-file-earmark-pdf'; $color = 'danger'; }
                elseif(in_array($ext, ['doc', 'docx'])) { $icon = 'bi-file-earmark-word'; $color = 'primary'; }
                elseif(in_array($ext, ['xls', 'xlsx'])) { $icon = 'bi-file-earmark-excel'; $color = 'success'; }
                elseif(in_array($ext, ['jpg', 'jpeg', 'png'])) { $icon = 'bi-file-earmark-image'; $color = 'info'; }
                elseif(in_array($ext, ['zip'])) { $icon = 'bi-file-earmark-zip'; $color = 'warning'; }
            @endphp
            
            <div class="col-md-6 col-lg-4 doc-card" data-id="{{ $document->id }}">
                <div class="card h-100 shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body d-flex align-items-center p-3">
                        <div class="bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.5rem;">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div class="overflow-hidden flex-grow-1">
                            <h6 class="mb-0 text-truncate fw-bold" title="{{ $document->file_name }}">{{ $document->file_name }}</h6>
                            <small class="text-muted">{{ number_format($document->size / 1024, 1) }} KB &bull; {{ $document->created_at->format('M d, Y') }}</small>
                        </div>
                        <div class="ms-2 dropdown">
                            <button class="btn btn-sm btn-link text-muted p-0 text-decoration-none" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="background: var(--secondary-bg); border: var(--glass-border);">
                                <li><a class="dropdown-item" href="{{ Storage::url($document->file_path) }}" target="_blank"><i class="bi bi-download me-2"></i>Download</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger delete-doc-btn" href="#" data-id="{{ $document->id }}"><i class="bi bi-trash me-2"></i>Delete</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5 text-muted empty-docs">
                <i class="bi bi-folder2-open fs-1 opacity-50 mb-3 d-block"></i>
                <p class="mb-0">No documents uploaded yet.</p>
            </div>
        @endforelse
    </div>
</div>
