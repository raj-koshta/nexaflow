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
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="mail-tab" data-bs-toggle="pill" data-bs-target="#mail" type="button" role="tab">
                    <i class="bi bi-envelope me-2"></i>Mail Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="redis-tab" data-bs-toggle="pill" data-bs-target="#redis" type="button" role="tab">
                    <i class="bi bi-server me-2"></i>Redis Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="search-tab" data-bs-toggle="pill" data-bs-target="#search" type="button" role="tab">
                    <i class="bi bi-search me-2"></i>Search Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="notification-tab" data-bs-toggle="pill" data-bs-target="#notification" type="button" role="tab">
                    <i class="bi bi-bell me-2"></i>Notification Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                    <i class="bi bi-shield-check me-2"></i>Security Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="appearance-tab" data-bs-toggle="pill" data-bs-target="#appearance" type="button" role="tab">
                    <i class="bi bi-palette me-2"></i>Appearance Settings
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

                        <!-- Mail Tab -->
                        <div class="tab-pane fade" id="mail" role="tabpanel">
                            <h5 class="fw-bold mb-4">Mail Settings</h5>
                            
                            <div class="mb-4">
                                <label for="mail_driver" class="form-label text-uppercase text-muted small letter-spacing-1">Mail Driver</label>
                                <select class="form-select" id="mail_driver" name="mail_driver">
                                    <option value="smtp" {{ setting('mail_driver', 'smtp') == 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="mailgun" {{ setting('mail_driver') == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                                    <option value="postmark" {{ setting('mail_driver') == 'postmark' ? 'selected' : '' }}>Postmark</option>
                                </select>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <label for="mail_host" class="form-label text-uppercase text-muted small letter-spacing-1">Mail Host</label>
                                    <input type="text" class="form-control" id="mail_host" name="mail_host" value="{{ setting('mail_host', 'smtp.mailtrap.io') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="mail_port" class="form-label text-uppercase text-muted small letter-spacing-1">Mail Port</label>
                                    <input type="number" class="form-control" id="mail_port" name="mail_port" value="{{ setting('mail_port', '2525') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="mail_username" class="form-label text-uppercase text-muted small letter-spacing-1">Username</label>
                                    <input type="text" class="form-control" id="mail_username" name="mail_username" value="{{ setting('mail_username', '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="mail_password" class="form-label text-uppercase text-muted small letter-spacing-1">Password</label>
                                    <input type="password" class="form-control" id="mail_password" name="mail_password" value="{{ setting('mail_password', '') }}">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="mail_encryption" class="form-label text-uppercase text-muted small letter-spacing-1">Encryption</label>
                                    <select class="form-select" id="mail_encryption" name="mail_encryption">
                                        <option value="tls" {{ setting('mail_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ setting('mail_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                        <option value="" {{ setting('mail_encryption') == '' ? 'selected' : '' }}>None</option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="mail_from_address" class="form-label text-uppercase text-muted small letter-spacing-1">From Address</label>
                                    <input type="email" class="form-control" id="mail_from_address" name="mail_from_address" value="{{ setting('mail_from_address', 'hello@example.com') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="mail_from_name" class="form-label text-uppercase text-muted small letter-spacing-1">From Name</label>
                                    <input type="text" class="form-control" id="mail_from_name" name="mail_from_name" value="{{ setting('mail_from_name', 'NexaFlow') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Redis Tab -->
                        <div class="tab-pane fade" id="redis" role="tabpanel">
                            <h5 class="fw-bold mb-4">Redis Settings</h5>

                            <div class="alert alert-warning border-0 shadow-sm mb-4" style="background: rgba(255, 193, 7, 0.1);">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle-fill fs-4 text-warning me-3"></i>
                                    <div>
                                        <p class="mb-0 small text-dark">Redis is required for AI queues, caching, and background jobs. Changing these settings may disrupt active jobs.</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="redis_host" class="form-label text-uppercase text-muted small letter-spacing-1">Redis Host</label>
                                <input type="text" class="form-control" id="redis_host" name="redis_host" value="{{ setting('redis_host', '127.0.0.1') }}">
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="redis_password" class="form-label text-uppercase text-muted small letter-spacing-1">Redis Password</label>
                                    <input type="password" class="form-control" id="redis_password" name="redis_password" value="{{ setting('redis_password', '') }}">
                                    <div class="form-text">Leave blank if no password is required.</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="redis_port" class="form-label text-uppercase text-muted small letter-spacing-1">Redis Port</label>
                                    <input type="number" class="form-control" id="redis_port" name="redis_port" value="{{ setting('redis_port', '6379') }}">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="redis_cache_prefix" class="form-label text-uppercase text-muted small letter-spacing-1">Cache Prefix</label>
                                <input type="text" class="form-control" id="redis_cache_prefix" name="redis_cache_prefix" value="{{ setting('redis_cache_prefix', 'nexaflow_cache_') }}">
                            </div>
                        </div>

                        <!-- Search Tab -->
                        <div class="tab-pane fade" id="search" role="tabpanel">
                            <h5 class="fw-bold mb-4">Search Settings</h5>
                            
                            <div class="mb-4">
                                <label for="search_driver" class="form-label text-uppercase text-muted small letter-spacing-1">Default Search Engine</label>
                                <select class="form-select" id="search_driver" name="search_driver">
                                    <option value="database" {{ setting('search_driver', 'database') == 'database' ? 'selected' : '' }}>Database (SQL LIKE Query)</option>
                                    <option value="meilisearch" {{ setting('search_driver') == 'meilisearch' ? 'selected' : '' }}>Meilisearch (Fast & Typo-Tolerant)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="search_result_limit" class="form-label text-uppercase text-muted small letter-spacing-1">Global Search Result Limit</label>
                                <input type="number" class="form-control" id="search_result_limit" name="search_result_limit" value="{{ setting('search_result_limit', '10') }}">
                                <div class="form-text">Maximum number of results to display per category in the global search dropdown.</div>
                            </div>
                        </div>

                        <!-- Notification Tab -->
                        <div class="tab-pane fade" id="notification" role="tabpanel">
                            <h5 class="fw-bold mb-4">Notification Settings</h5>

                            <div class="mb-4">
                                <div class="form-check form-switch fs-5 mb-2">
                                    <input type="hidden" name="notify_email" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="notify_email" name="notify_email" value="1" {{ setting('notify_email', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-6 ms-2" for="notify_email">Enable Email Notifications</label>
                                </div>
                                <div class="form-text ms-5">Send notifications directly to user emails.</div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch fs-5 mb-2">
                                    <input type="hidden" name="notify_database" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="notify_database" name="notify_database" value="1" {{ setting('notify_database', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-6 ms-2" for="notify_database">Enable Database Notifications</label>
                                </div>
                                <div class="form-text ms-5">Show notifications in the app's notification dropdown.</div>
                            </div>
                            
                            <hr class="my-4">

                            <div class="mb-4">
                                <label for="notification_retention_days" class="form-label text-uppercase text-muted small letter-spacing-1">Retention Period (Days)</label>
                                <input type="number" class="form-control" id="notification_retention_days" name="notification_retention_days" value="{{ setting('notification_retention_days', '30') }}">
                                <div class="form-text">Read database notifications older than this many days will be automatically deleted.</div>
                            </div>
                        </div>

                        <!-- Security Tab -->
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <h5 class="fw-bold mb-4">Security Settings</h5>
                            
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="password_min_length" class="form-label text-uppercase text-muted small letter-spacing-1">Minimum Password Length</label>
                                    <input type="number" class="form-control" id="password_min_length" name="password_min_length" value="{{ setting('password_min_length', '8') }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="max_login_attempts" class="form-label text-uppercase text-muted small letter-spacing-1">Max Login Attempts</label>
                                    <input type="number" class="form-control" id="max_login_attempts" name="max_login_attempts" value="{{ setting('max_login_attempts', '5') }}">
                                    <div class="form-text">Lockout occurs after this many failed attempts.</div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="session_timeout" class="form-label text-uppercase text-muted small letter-spacing-1">Session Timeout (Minutes)</label>
                                <input type="number" class="form-control" id="session_timeout" name="session_timeout" value="{{ setting('session_timeout', '120') }}">
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch fs-5 mb-2">
                                    <input type="hidden" name="require_password_complexity" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="require_password_complexity" name="require_password_complexity" value="1" {{ setting('require_password_complexity', '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-6 ms-2" for="require_password_complexity">Require Password Complexity</label>
                                </div>
                                <div class="form-text ms-5">Require at least one uppercase, one lowercase, one number, and one symbol.</div>
                            </div>
                        </div>

                        <!-- Appearance Tab -->
                        <div class="tab-pane fade" id="appearance" role="tabpanel">
                            <h5 class="fw-bold mb-4">Appearance Settings</h5>
                            
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="theme_mode" class="form-label text-uppercase text-muted small letter-spacing-1">Default Theme Mode</label>
                                    <select class="form-select" id="theme_mode" name="theme_mode">
                                        <option value="system" {{ setting('theme_mode', 'system') == 'system' ? 'selected' : '' }}>System Default</option>
                                        <option value="light" {{ setting('theme_mode') == 'light' ? 'selected' : '' }}>Always Light</option>
                                        <option value="dark" {{ setting('theme_mode') == 'dark' ? 'selected' : '' }}>Always Dark</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="form-check form-switch fs-5 mb-2">
                                    <input type="hidden" name="enable_animations" value="0">
                                    <input class="form-check-input" type="checkbox" role="switch" id="enable_animations" name="enable_animations" value="1" {{ setting('enable_animations', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label fs-6 ms-2" for="enable_animations">Enable UI Animations</label>
                                </div>
                                <div class="form-text ms-5">Turn off for a less distractive, static experience.</div>
                            </div>

                            <hr class="my-4">

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="primary_color" class="form-label text-uppercase text-muted small letter-spacing-1">Primary Color (Hex)</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color border-end-0" id="primary_color_picker" value="{{ setting('primary_color', '#8b5cf6') }}" title="Choose your color">
                                        <input type="text" class="form-control" id="primary_color" name="primary_color" value="{{ setting('primary_color', '#8b5cf6') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="accent_color" class="form-label text-uppercase text-muted small letter-spacing-1">Accent Color (Hex)</label>
                                    <div class="input-group">
                                        <input type="color" class="form-control form-control-color border-end-0" id="accent_color_picker" value="{{ setting('accent_color', '#ec4899') }}" title="Choose your color">
                                        <input type="text" class="form-control" id="accent_color" name="accent_color" value="{{ setting('accent_color', '#ec4899') }}">
                                    </div>
                                </div>
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

        // Sync Color Pickers with Text Inputs
        $('#primary_color_picker').on('input', function() {
            $('#primary_color').val($(this).val());
        });
        $('#primary_color').on('input', function() {
            $('#primary_color_picker').val($(this).val());
        });

        $('#accent_color_picker').on('input', function() {
            $('#accent_color').val($(this).val());
        });
        $('#accent_color').on('input', function() {
            $('#accent_color_picker').val($(this).val());
        });
    });
</script>
@endpush
