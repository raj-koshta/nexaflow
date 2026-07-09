@extends('layouts.master')

@section('title', 'Client Profile')

@push('custom-css')
<style>
    .nav-pills .nav-link {
        color: var(--text-muted);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        transition: all 0.2s ease;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .nav-pills .nav-link:hover {
        background: rgba(139, 92, 246, 0.1);
        color: var(--accent);
    }
    .nav-pills .nav-link.active {
        background: var(--accent);
        color: white;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }
    
    .timeline-container {
        position: relative;
        padding-left: 20px;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 2px;
        background: var(--border-color);
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('clients.index') }}" class="text-decoration-none">Clients</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($client->company_name, 20) }}</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">Client Profile</h1>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-secondary shadow-sm me-2" onclick="location.href='{{ route('clients.index') }}'">
            <i class="bi bi-arrow-left me-1"></i> Back
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Summary -->
    <div class="col-lg-4">
        <!-- Client Info Card -->
        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 56px; height: 56px; font-size: 1.5rem;">
                        {{ substr($client->company_name, 0, 1) }}
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $client->company_name }}</h4>
                        @if($client->status === 'Active')
                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill"><i class="bi bi-check-circle me-1"></i>Active</span>
                        @else
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 rounded-pill"><i class="bi bi-pause-circle me-1"></i>Inactive</span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Email</small>
                    <a href="mailto:{{ $client->email }}" class="text-decoration-none fw-medium text-main"><i class="bi bi-envelope me-2 text-muted"></i>{{ $client->email }}</a>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Phone</small>
                    <a href="tel:{{ $client->phone }}" class="text-decoration-none fw-medium text-main"><i class="bi bi-telephone me-2 text-muted"></i>{{ $client->phone ?: 'N/A' }}</a>
                </div>
                
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Website</small>
                    @if($client->website)
                        <a href="{{ $client->website }}" target="_blank" class="text-decoration-none fw-medium text-main"><i class="bi bi-globe me-2 text-muted"></i>{{ $client->website }}</a>
                    @else
                        <span class="text-muted"><i class="bi bi-globe me-2"></i>N/A</span>
                    @endif
                </div>

                <div class="mb-0">
                    <small class="text-muted d-block mb-1">Address</small>
                    <span class="text-main fw-medium"><i class="bi bi-geo-alt me-2 text-muted"></i>{{ $client->address ?: 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Contacts Summary Card -->
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-bottom p-3 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                <h6 class="fw-bold mb-0">Contacts ({{ $client->contacts->count() }})</h6>
                <a href="{{ route('contacts.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none text-muted">View All</a>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @forelse($client->contacts->take(3) as $contact)
                    <li class="list-group-item bg-transparent p-3" style="border-color: var(--border-color) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-0">
                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                    @if($contact->is_primary)
                                        <i class="bi bi-star-fill text-warning ms-1" style="font-size: 0.75rem;" title="Primary Contact"></i>
                                    @endif
                                </h6>
                                <small class="text-muted">{{ $contact->job_title ?: 'No Title' }}</small>
                            </div>
                            <a href="mailto:{{ $contact->email }}" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="bi bi-envelope"></i></a>
                        </div>
                    </li>
                    @empty
                    <li class="list-group-item bg-transparent p-4 text-center text-muted">
                        No contacts associated.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column: Tabs -->
    <div class="col-lg-8">
        <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="timeline-tab" data-bs-toggle="pill" data-bs-target="#timeline" type="button" role="tab" aria-controls="timeline" aria-selected="true">
                    <i class="bi bi-clock-history me-2"></i>Timeline
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notes-tab" data-bs-toggle="pill" data-bs-target="#notes" type="button" role="tab" aria-controls="notes" aria-selected="false">
                    <i class="bi bi-journal-text me-2"></i>Internal Notes
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="documents-tab" data-bs-toggle="pill" data-bs-target="#documents" type="button" role="tab" aria-controls="documents" aria-selected="false">
                    <i class="bi bi-folder2-open me-2"></i>Documents
                </button>
            </li>
        </ul>

        <div class="tab-content" id="profileTabsContent">
            <!-- Timeline Tab -->
            <div class="tab-pane fade show active" id="timeline" role="tabpanel" aria-labelledby="timeline-tab">
                <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0">Activity History</h5>
                            <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-primary">View Full Logs</a>
                        </div>
                        
                        @if($client->activities->count() > 0)
                            <div class="timeline-container">
                                @foreach($client->activities as $activity)
                                    @php
                                        $icon = 'bi-activity';
                                        $color = 'primary';
                                        switch(strtolower($activity->type)) {
                                            case 'phone call': $icon = 'bi-telephone-fill'; $color = 'info'; break;
                                            case 'meeting': $icon = 'bi-people-fill'; $color = 'success'; break;
                                            case 'email': $icon = 'bi-envelope-fill'; $color = 'warning'; break;
                                            case 'demo': $icon = 'bi-display'; $color = 'danger'; break;
                                        }
                                    @endphp
                                    
                                    <div class="position-relative mb-4 ps-4">
                                        <div class="position-absolute bg-{{ $color }} text-white rounded-circle d-flex align-items-center justify-content-center" 
                                             style="left: -16px; width: 32px; height: 32px; top: 0; z-index: 2;">
                                            <i class="bi {{ $icon }} small"></i>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="fw-bold mb-0">{{ $activity->title }}</h6>
                                            <span class="text-muted small">{{ $activity->activity_date->format('M d, Y g:i A') }}</span>
                                        </div>
                                        <p class="text-main mb-1" style="font-size: 0.9rem;">{{ $activity->description }}</p>
                                        <small class="text-muted">Logged by {{ $activity->creator->name }}</small>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5 text-muted">
                                <i class="bi bi-clock-history fs-1 opacity-50 mb-3 d-block"></i>
                                <p class="mb-0">No activities recorded for this client yet.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Notes Tab -->
            <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                @include('clients.partials.notes')
            </div>

            <!-- Documents Tab -->
            <div class="tab-pane fade" id="documents" role="tabpanel" aria-labelledby="documents-tab">
                @include('clients.partials.documents')
            </div>
        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    // Add Note
    $('#addNoteForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const $label = $btn.find('.indicator-label');
        const $progress = $btn.find('.indicator-progress');
        
        $btn.prop('disabled', true);
        $label.addClass('d-none');
        $progress.removeClass('d-none');

        $.ajax({
            url: '{{ route("notes.store") }}',
            type: 'POST',
            data: $form.serialize(),
            success: function(response) {
                showToast('Success', response.message, 'success');
                $form[0].reset();
                
                // Hide empty state if exists
                $('.empty-notes').addClass('d-none');
                
                // Prepend new note card
                const note = response.note;
                const html = `
                    <div class="card shadow-sm border-0 mb-3 note-card" data-id="${note.id}" style="background: var(--card-bg); border: var(--glass-border); display: none;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px;">
                                        ${note.creator.name.substring(0, 1)}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">${note.creator.name}</h6>
                                        <small class="text-muted">Just now</small>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-link text-danger p-0 delete-note-btn" data-id="${note.id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <p class="mb-0 text-main whitespace-pre-wrap">${note.content}</p>
                        </div>
                    </div>
                `;
                $('#notesList').prepend(html);
                $('.note-card').first().slideDown(300);
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error adding note', 'error');
            },
            complete: function() {
                $btn.prop('disabled', false);
                $label.removeClass('d-none');
                $progress.addClass('d-none');
            }
        });
    });

    // Delete Note
    $(document).on('click', '.delete-note-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $card = $(this).closest('.note-card');
        
        confirmAction('Delete Note?', 'Are you sure you want to delete this internal note?', function() {
            $.ajax({
                url: `/notes/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    $card.slideUp(300, function() {
                        $(this).remove();
                        if($('.note-card').length === 0) {
                            $('.empty-notes').removeClass('d-none');
                        }
                    });
                },
                error: function(xhr) {
                    showToast('Error', 'Failed to delete note.', 'error');
                }
            });
        });
    });

    // Upload Document
    $('#uploadDocForm').on('submit', function(e) {
        e.preventDefault();
        
        const $form = $(this);
        const $btn = $form.find('button[type="submit"]');
        const $label = $btn.find('.indicator-label');
        const $progress = $btn.find('.indicator-progress');
        
        const formData = new FormData(this);
        
        $btn.prop('disabled', true);
        $label.addClass('d-none');
        $progress.removeClass('d-none');

        $.ajax({
            url: '{{ route("documents.store") }}',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                showToast('Success', response.message, 'success');
                $form[0].reset();
                
                // For simplicity, reload the page on document upload to refresh links and icons
                // (Alternatively we could append the card via JS like we did with notes)
                location.reload();
            },
            error: function(xhr) {
                showToast('Error', xhr.responseJSON?.message || 'Error uploading document', 'error');
                $btn.prop('disabled', false);
                $label.removeClass('d-none');
                $progress.addClass('d-none');
            }
        });
    });

    // Delete Document
    $(document).on('click', '.delete-doc-btn', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const $card = $(this).closest('.doc-card');
        
        confirmAction('Delete Document?', 'Are you sure you want to permanently delete this file?', function() {
            $.ajax({
                url: `/documents/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    showToast('Success', response.message, 'success');
                    $card.fadeOut(300, function() {
                        $(this).remove();
                        if($('.doc-card').length === 0) {
                            $('.empty-docs').removeClass('d-none');
                        }
                    });
                },
                error: function(xhr) {
                    showToast('Error', 'Failed to delete document.', 'error');
                }
            });
        });
    });
</script>
@endpush
