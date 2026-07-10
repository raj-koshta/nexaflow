@extends('layouts.master')

@section('title', 'System Settings')

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
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">System Settings</h1>
        <p class="text-muted">Configure global application settings and preferences.</p>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Tabs -->
    <div class="col-lg-3">
        <ul class="nav nav-pills flex-column mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start active" id="general-tab" data-bs-toggle="pill" data-bs-target="#general" type="button" role="tab">
                    <i class="bi bi-gear me-2"></i>General
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="company-tab" data-bs-toggle="pill" data-bs-target="#company" type="button" role="tab">
                    <i class="bi bi-building me-2"></i>Company
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="ai-tab" data-bs-toggle="pill" data-bs-target="#ai" type="button" role="tab">
                    <i class="bi bi-robot me-2"></i>AI Assistant
                </button>
            </li>
        </ul>
    </div>

    <!-- Right Column: Content -->
    <div class="col-lg-9">
        <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4">
                
                <form id="settingsUpdateForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="tab-content" id="settingsTabsContent">
                        
                        <!-- General Tab -->
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <h5 class="fw-bold mb-4">General Settings</h5>
                            
                            <div class="mb-4">
                                <label for="app_name" class="form-label text-uppercase text-muted small letter-spacing-1">Application Name</label>
                                <input type="text" class="form-control" id="app_name" name="app_name" value="{{ setting('app_name', 'NexaFlow') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label for="support_email" class="form-label text-uppercase text-muted small letter-spacing-1">Support Email</label>
                                <input type="email" class="form-control" id="support_email" name="support_email" value="{{ setting('support_email', 'support@example.com') }}">
                            </div>

                            <div class="mb-4">
                                <label for="timezone" class="form-label text-uppercase text-muted small letter-spacing-1">System Timezone</label>
                                <select class="form-select" id="timezone" name="timezone">
                                    <option value="UTC" {{ setting('timezone') == 'UTC' ? 'selected' : '' }}>UTC</option>
                                    <option value="America/New_York" {{ setting('timezone') == 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                    <option value="Europe/London" {{ setting('timezone') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                    <option value="Asia/Tokyo" {{ setting('timezone') == 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo (JST)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Company Tab -->
                        <div class="tab-pane fade" id="company" role="tabpanel">
                            <h5 class="fw-bold mb-4">Company Details</h5>
                            
                            <div class="mb-4">
                                <label for="company_name" class="form-label text-uppercase text-muted small letter-spacing-1">Company Name</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" value="{{ setting('company_name', '') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label for="company_phone" class="form-label text-uppercase text-muted small letter-spacing-1">Phone Number</label>
                                <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ setting('company_phone', '') }}">
                            </div>
                            
                            <div class="mb-4">
                                <label for="company_address" class="form-label text-uppercase text-muted small letter-spacing-1">Address</label>
                                <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ setting('company_address', '') }}</textarea>
                            </div>
                        </div>

                        <!-- AI Tab -->
                        <div class="tab-pane fade" id="ai" role="tabpanel">
                            <h5 class="fw-bold mb-4">AI Configuration</h5>
                            
                            <div class="alert alert-info border-0 shadow-sm mb-4" style="background: rgba(13, 202, 240, 0.1);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill fs-4 text-info me-3"></i>
                                    <div>
                                        <p class="mb-0 small">The AI Assistant requires a valid Google Gemini API key to function. By default, it will fall back to your <code>.env</code> file if this is blank.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="gemini_api_key" class="form-label text-uppercase text-muted small letter-spacing-1">Gemini API Key</label>
                                <input type="password" class="form-control" id="gemini_api_key" name="gemini_api_key" value="{{ setting('gemini_api_key', '') }}" placeholder="AIzaSy...">
                            </div>
                            
                            <div class="mb-4">
                                <label for="ai_model" class="form-label text-uppercase text-muted small letter-spacing-1">Default Model</label>
                                <select class="form-select" id="ai_model" name="ai_model">
                                    <option value="gemini-2.5-flash" {{ setting('ai_model', 'gemini-2.5-flash') == 'gemini-2.5-flash' ? 'selected' : '' }}>Gemini 2.5 Flash (Recommended)</option>
                                    <option value="gemini-2.5-pro" {{ setting('ai_model') == 'gemini-2.5-pro' ? 'selected' : '' }}>Gemini 2.5 Pro</option>
                                    <option value="gemini-1.5-flash" {{ setting('ai_model') == 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash</option>
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="d-flex align-items-center gap-3 mt-4 pt-4 border-top" style="border-color: var(--border-color) !important;">
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">Save All Settings</button>
                        <span class="text-success small fw-medium d-none" id="settingsSaveMsg"><i class="bi bi-check-circle me-1"></i>Settings saved successfully.</span>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $('#settingsUpdateForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            const $btn = $(this).find('button[type="submit"]');
            const originalText = $btn.text();
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Saving...');
            
            $.ajax({
                url: '{{ route('settings.update') }}',
                type: 'PUT',
                data: $(this).serialize(),
                success: function(res) {
                    $btn.prop('disabled', false).text(originalText);
                    $('#settingsSaveMsg').removeClass('d-none').show();
                    setTimeout(() => $('#settingsSaveMsg').fadeOut(), 3000);
                    showToast('Success', res.message, 'success');
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).text(originalText);
                    showToast('Error', xhr.responseJSON?.message || 'Failed to update settings', 'error');
                }
            });
        });
    });
</script>
@endpush
