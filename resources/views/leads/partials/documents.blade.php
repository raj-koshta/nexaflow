<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Documents</h5>
    </div>

    <!-- Upload Document Form -->
    <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body">
            <form id="uploadDocForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label small text-muted">File Name (Optional)</label>
                        <input type="text" class="form-control bg-transparent" name="file_name" placeholder="e.g. Contract">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small text-muted">Select File</label>
                        <input type="file" class="form-control bg-transparent" name="file" required>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <span class="indicator-label">Upload</span>
                            <span class="indicator-progress d-none">
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents List -->
    <div class="row g-3" id="documentsList">
        @forelse($lead->documents->sortByDesc('created_at') as $document)
            <div class="col-md-6 doc-card" data-id="{{ $document->id }}">
                <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body p-3 d-flex align-items-center">
                        @php
                            $docIcon = 'bi-file-earmark-text';
                            $docColor = 'primary';
                            if(str_contains($document->mime_type, 'pdf')) { $docIcon = 'bi-file-earmark-pdf-fill'; $docColor = 'danger'; }
                            if(str_contains($document->mime_type, 'image')) { $docIcon = 'bi-file-earmark-image-fill'; $docColor = 'success'; }
                            if(str_contains($document->mime_type, 'spreadsheet') || str_contains($document->mime_type, 'excel')) { $docIcon = 'bi-file-earmark-excel-fill'; $docColor = 'success'; }
                        @endphp
                        
                        <div class="bg-{{ $docColor }} bg-opacity-10 text-{{ $docColor }} rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                            <i class="bi {{ $docIcon }} fs-4"></i>
                        </div>
                        
                        <div class="flex-grow-1 overflow-hidden">
                            <h6 class="mb-0 fw-medium text-truncate text-main" title="{{ $document->file_name }}">{{ $document->file_name }}</h6>
                            <div class="d-flex align-items-center mt-1">
                                <small class="text-muted me-2">{{ number_format($document->size / 1024, 2) }} KB</small>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $document->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        
                        <div class="dropdown ms-2">
                            <button class="btn btn-sm btn-link text-muted p-0" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="background: var(--secondary-bg); border: var(--glass-border) !important;">
                                <li><a class="dropdown-item py-2 text-main" href="{{ route('documents.download', $document->id) }}"><i class="bi bi-download me-2 text-muted"></i>Download</a></li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
                                <li><button class="dropdown-item py-2 text-danger delete-doc-btn" data-id="{{ $document->id }}"><i class="bi bi-trash me-2"></i>Delete</button></li>
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