@extends('layouts.master')

@section('title', 'AI Prompt Templates')

@push('custom-css')
<style>
    .glass-card {
        background: var(--card-bg);
        border: var(--glass-border);
        border-radius: 12px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
    }
    .status-badge {
        font-size: 0.7rem;
        letter-spacing: 0.5px;
    }
    .template-description {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .ai-gradient-text {
        background: linear-gradient(135deg, #8b5cf6, #3b82f6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom" style="border-color: var(--border-color) !important;">
    <div>
        <h1 class="h2 fw-bold mb-0 d-flex align-items-center">
            <div class="avatar-sm text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="background: linear-gradient(135deg, #8b5cf6, #3b82f6); width: 40px; height: 40px;">
                <i class="bi bi-magic"></i>
            </div>
            AI Prompt Templates
        </h1>
        <p class="text-muted mb-0 mt-2">Manage reusable AI instructions for your team.</p>
    </div>
    <button class="btn text-white px-4 py-2 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none;" onclick="openTemplateForm()">
        <i class="bi bi-plus-lg me-1"></i> New Template
    </button>
</div>

<div class="row g-4">
    @forelse($templates as $template)
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-4 h-100 d-flex flex-column position-relative">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="fw-bold mb-0 text-main">{{ $template->name }}</h5>
                <span class="badge {{ $template->is_active ? 'bg-success' : 'bg-secondary' }} bg-opacity-10 text-{{ $template->is_active ? 'success' : 'secondary' }} rounded-pill px-2 status-badge text-uppercase">
                    {{ $template->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            
            <p class="text-muted small template-description flex-grow-1 mb-4">
                {{ $template->description ?: 'No description provided.' }}
            </p>
            
            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top" style="border-color: var(--border-color) !important;">
                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-clock me-1"></i>{{ $template->updated_at->diffForHumans() }}</span>
                <div class="d-flex gap-1">
                    <button class="btn btn-sm btn-link text-primary p-0 edit-btn" data-template="{{ json_encode($template) }}" title="Edit">
                        <i class="bi bi-pencil-square fs-5"></i>
                    </button>
                    <button class="btn btn-sm btn-link text-danger p-0 delete-btn" data-id="{{ $template->id }}" title="Delete">
                        <i class="bi bi-trash fs-5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="bi bi-magic text-muted opacity-25 d-block mb-3" style="font-size: 4rem;"></i>
        <h5 class="fw-bold text-main">No Templates Found</h5>
        <p class="text-muted">You haven't created any AI prompt templates yet.</p>
        <button class="btn btn-outline-primary rounded-pill px-4 mt-2" onclick="openTemplateForm()">Create Your First Template</button>
    </div>
    @endforelse
</div>

<!-- Offcanvas Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="templateOffcanvas" style="background: var(--primary-bg); border-left: var(--glass-border); width: 500px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important;">
        <h5 class="offcanvas-title fw-bold" id="offcanvasTitle">Create Template</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <form id="templateForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" id="template_id">

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold letter-spacing-1">Template Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="name" id="name" required style="border-radius: 8px;">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold letter-spacing-1">Description</label>
                <textarea class="form-control" name="description" id="description" rows="2" style="border-radius: 8px;"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase fw-bold letter-spacing-1 d-flex justify-content-between">
                    <span>System Prompt <span class="text-danger">*</span></span>
                    <i class="bi bi-info-circle text-primary" title="Instructions defining the AI's role and behavior."></i>
                </label>
                <textarea class="form-control font-monospace text-sm" name="system_prompt" id="system_prompt" rows="4" required style="border-radius: 8px; font-size: 0.85rem;" placeholder="You are an expert sales representative..."></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small text-uppercase fw-bold letter-spacing-1 d-flex justify-content-between">
                    <span>User Prompt <span class="text-danger">*</span></span>
                    <i class="bi bi-info-circle text-primary" title="The actual request or template structure sent to the AI."></i>
                </label>
                <textarea class="form-control font-monospace text-sm" name="user_prompt" id="user_prompt" rows="5" required style="border-radius: 8px; font-size: 0.85rem;" placeholder="Write a follow-up email to @{{ client.name }} about @{{ project.title }}..."></textarea>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" checked>
                <label class="form-check-label" for="is_active">Template is Active</label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn text-white rounded-pill px-4 shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #3b82f6); border: none;">Save Template</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('templateOffcanvas'));

    function openTemplateForm(template = null) {
        const form = document.getElementById('templateForm');
        form.reset();

        if (template) {
            document.getElementById('offcanvasTitle').innerText = 'Edit Template';
            document.getElementById('formMethod').value = 'PUT';
            document.getElementById('template_id').value = template.id;
            
            document.getElementById('name').value = template.name;
            document.getElementById('description').value = template.description || '';
            document.getElementById('system_prompt').value = template.system_prompt;
            document.getElementById('user_prompt').value = template.user_prompt;
            document.getElementById('is_active').checked = template.is_active;
        } else {
            document.getElementById('offcanvasTitle').innerText = 'Create Template';
            document.getElementById('formMethod').value = 'POST';
            document.getElementById('template_id').value = '';
            document.getElementById('is_active').checked = true;
        }
        
        offcanvas.show();
    }

    $(document).ready(function() {
        // Edit Button
        $('.edit-btn').on('click', function() {
            openTemplateForm($(this).data('template'));
        });

        // Delete Button
        $('.delete-btn').on('click', function() {
            const id = $(this).data('id');
            confirmAction('Delete Template?', 'Are you sure you want to delete this prompt template? This cannot be undone.', function() {
                $.ajax({
                    url: `/ai/prompt-templates/${id}`,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        showToast('Success', res.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    }
                });
            });
        });

        // Form Submit
        $('#templateForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#template_id').val();
            const method = $('#formMethod').val();
            const url = id ? `/ai/prompt-templates/${id}` : "{{ route('ai.templates.store') }}";

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function(res) {
                    showToast('Success', res.message, 'success');
                    offcanvas.hide();
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to save template.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    showToast('Error', errorMessage, 'error');
                }
            });
        });
    });
</script>
@endpush
