<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Internal Notes</h5>
    </div>

    <!-- Add Note Form -->
    <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body">
            <form id="addNoteForm">
                @csrf
                <input type="hidden" name="client_id" value="{{ $client->id }}">
                <div class="mb-3">
                    <textarea class="form-control bg-transparent" name="content" rows="3" placeholder="Write a note... (Only visible to team members)" required></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary btn-sm px-4">
                        <span class="indicator-label">Add Note</span>
                        <span class="indicator-progress d-none">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notes List -->
    <div id="notesList">
        @forelse($client->notes->sortByDesc('created_at') as $note)
            <div class="card shadow-sm border-0 mb-3 note-card" data-id="{{ $note->id }}" style="background: var(--card-bg); border: var(--glass-border);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 32px; height: 32px;">
                                {{ substr($note->creator->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $note->creator->name }}</h6>
                                <small class="text-muted">{{ $note->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if(Auth::id() === $note->created_by)
                            <button class="btn btn-sm btn-link text-danger p-0 delete-note-btn" data-id="{{ $note->id }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        @endif
                    </div>
                    <p class="mb-0 text-main whitespace-pre-wrap">{{ $note->content }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-5 text-muted empty-notes">
                <i class="bi bi-journal-x fs-1 opacity-50 mb-3 d-block"></i>
                <p class="mb-0">No notes added yet.</p>
            </div>
        @endforelse
    </div>
</div>
