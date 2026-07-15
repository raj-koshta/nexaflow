@extends('layouts.master')

@section('title', 'Reset Password')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 64px);">
    <div class="col-md-6 col-lg-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="background: linear-gradient(to right, var(--text-main), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Create New Password</h2>
            <p class="text-muted">Enter your new password below.</p>
        </div>
        <div class="card p-2">
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); color: #ff6b6b;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.store') }}" id="resetPasswordForm">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus readonly>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control border-start-0" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5" id="resetBtn">
                        Reset Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $('#resetPasswordForm').submit(function(e) {
        $('#resetBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...');
    });
</script>
@endpush
