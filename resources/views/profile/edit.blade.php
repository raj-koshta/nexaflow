@extends('layouts.master')

@section('title', 'Profile')

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
    
    .profile-avatar-large {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 4px solid var(--primary-bg);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    
    .profile-header-card {
        background: linear-gradient(135deg, rgba(139, 92, 246, 0.8), rgba(99, 102, 241, 0.8));
        border: none;
        border-radius: 12px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .profile-header-card::before {
        content: '';
        position: absolute;
        top: 0; right: 0; bottom: 0; left: 0;
        background: url('data:image/svg+xml;utf8,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>') repeat;
        opacity: 0.3;
        z-index: 1;
    }
    
    .profile-content {
        position: relative;
        z-index: 2;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">My Profile</h1>
        <p class="text-muted">Manage your personal information and security settings.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card profile-header-card shadow">
            <div class="card-body p-4 profile-content">
                <div class="d-flex flex-column flex-md-row align-items-center gap-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=8b5cf6&size=200" alt="{{ $user->name }}" class="profile-avatar-large">
                    <div class="text-center text-md-start">
                        <h2 class="fw-bold mb-1">{{ $user->name }}</h2>
                        <p class="mb-2 opacity-75 fs-5"><i class="bi bi-envelope me-2"></i>{{ $user->email }}</p>
                        <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-medium shadow-sm">
                            <i class="bi bi-person-badge me-1"></i>Administrator
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Tabs -->
    <div class="col-lg-3">
        <ul class="nav nav-pills flex-column mb-4" id="profileTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start active" id="overview-tab" data-bs-toggle="pill" data-bs-target="#overview" type="button" role="tab">
                    <i class="bi bi-grid me-2"></i>Overview
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings" type="button" role="tab">
                    <i class="bi bi-person-gear me-2"></i>Profile Settings
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link w-100 text-start" id="security-tab" data-bs-toggle="pill" data-bs-target="#security" type="button" role="tab">
                    <i class="bi bi-shield-lock me-2"></i>Security
                </button>
            </li>
        </ul>
    </div>

    <!-- Right Column: Content -->
    <div class="col-lg-9">
        <div class="tab-content" id="profileTabsContent">
            
            <!-- Overview Tab -->
            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
                            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                                <h5 class="fw-bold mb-0"><i class="bi bi-briefcase me-2 text-primary"></i>My Assigned Projects</h5>
                            </div>
                            <div class="card-body">
                                @if($assignedProjects->count() > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach($assignedProjects as $project)
                                        <li class="list-group-item bg-transparent px-0 py-3" style="border-color: var(--border-color);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 fw-medium"><a href="{{ route('projects.show', $project->id) }}" class="text-decoration-none" style="color: var(--text-main);">{{ $project->name }}</a></h6>
                                                    <small class="text-muted">{{ $project->client->company_name }}</small>
                                                </div>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill">{{ $project->status }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center py-4 mb-0">No projects assigned.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
                            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                                <h5 class="fw-bold mb-0"><i class="bi bi-check2-square me-2 text-primary"></i>Pending Tasks</h5>
                            </div>
                            <div class="card-body">
                                @if($assignedTasks->count() > 0)
                                    <ul class="list-group list-group-flush">
                                        @foreach($assignedTasks as $task)
                                        <li class="list-group-item bg-transparent px-0 py-3" style="border-color: var(--border-color);">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1 fw-medium">{{ $task->title }}</h6>
                                                    <small class="text-muted"><i class="bi bi-calendar me-1"></i> Due: {{ $task->due_date ? $task->due_date->format('M d') : 'N/A' }}</small>
                                                </div>
                                                @php
                                                    $pColor = match($task->priority) {
                                                        'Urgent' => 'danger', 'High' => 'warning', 'Medium' => 'info', 'Low' => 'secondary', default => 'secondary'
                                                    };
                                                @endphp
                                                <span class="badge bg-{{ $pColor }} bg-opacity-10 text-{{ $pColor }} rounded-pill px-2">{{ $task->priority }}</span>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-muted text-center py-4 mb-0">No pending tasks. You're all caught up!</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Settings Tab -->
            <div class="tab-pane fade" id="settings" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Profile Information</h5>
                        <p class="text-muted mb-4 small">Update your account's profile information and email address.</p>
                        
                        <form id="profileUpdateForm">
                            @csrf
                            <div class="mb-4">
                                <label for="name" class="form-label text-uppercase text-muted small letter-spacing-1">Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="form-label text-uppercase text-muted small letter-spacing-1">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-4">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Changes</button>
                                <span class="text-success small fw-medium d-none" id="profileSaveMsg"><i class="bi bi-check-circle me-1"></i>Saved.</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">Update Password</h5>
                        <p class="text-muted mb-4 small">Ensure your account is using a long, random password to stay secure.</p>
                        
                        <form id="passwordUpdateForm">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="current_password" class="form-label text-uppercase text-muted small letter-spacing-1">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <div class="invalid-feedback" id="current_password_err"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label text-uppercase text-muted small letter-spacing-1">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="invalid-feedback" id="password_err"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label text-uppercase text-muted small letter-spacing-1">Confirm Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>

                            <div class="d-flex align-items-center gap-3 mt-4">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">Update Password</button>
                                <span class="text-success small fw-medium d-none" id="passwordSaveMsg"><i class="bi bi-check-circle me-1"></i>Saved.</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        // Profile Update
        $('#profileUpdateForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route('profile.update') }}',
                type: 'PUT',
                data: $(this).serialize(),
                success: function(res) {
                    $('#profileSaveMsg').removeClass('d-none').show();
                    setTimeout(() => $('#profileSaveMsg').fadeOut(), 3000);
                    showToast('Success', res.message, 'success');
                    
                    // Update header name if changed
                    const newName = $('#name').val();
                    $('.dropdown-toggle .fw-medium').text(newName);
                    $('.profile-avatar-large').attr('src', 'https://ui-avatars.com/api/?name=' + encodeURIComponent(newName) + '&background=ffffff&color=8b5cf6&size=200');
                    $('.profile-content h2').text(newName);
                    $('#email').val($('#email').val()); // Refresh
                    $('.profile-content p').html('<i class="bi bi-envelope me-2"></i>' + $('#email').val());
                },
                error: function(xhr) {
                    showToast('Error', xhr.responseJSON?.message || 'Failed to update profile', 'error');
                }
            });
        });
        
        // Password Update
        $('#passwordUpdateForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $form = $(this);
            
            // Clear errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            
            $.ajax({
                url: '{{ route('profile.password') }}',
                type: 'PUT',
                data: $form.serialize(),
                success: function(res) {
                    $form[0].reset();
                    $('#passwordSaveMsg').removeClass('d-none').show();
                    setTimeout(() => $('#passwordSaveMsg').fadeOut(), 3000);
                    showToast('Success', res.message, 'success');
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if(errors.current_password) {
                            $('#current_password').addClass('is-invalid');
                            $('#current_password_err').text(errors.current_password[0]);
                        }
                        if(errors.password) {
                            $('#password').addClass('is-invalid');
                            $('#password_err').text(errors.password[0]);
                        }
                    } else {
                        showToast('Error', xhr.responseJSON?.message || 'Failed to update password', 'error');
                    }
                }
            });
        });
    });
</script>
@endpush
