@extends('layouts.master')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 64px);">
    <div class="col-md-6 col-lg-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="background: linear-gradient(to right, var(--text-main), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Welcome Back</h2>
            <p class="text-muted">Sign in to continue to NexaFlow</p>
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

                <form method="POST" action="{{ route('login.post') }}" id="loginForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label d-flex justify-content-between">
                            <span>Password</span>
                            <a href="{{ route('password.request') }}" style="color: var(--accent); text-decoration: none; font-size: 0.85rem;">Forgot password?</a>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="mb-4 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember" style="background-color: transparent; border-color: rgba(255,255,255,0.2);">
                        <label class="form-check-label text-muted" for="remember">Remember me for 30 days</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5" id="loginBtn">
                        Sign In
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Don't have an account? <a href="{{ route('register') }}" class="fw-medium" style="color: var(--accent); text-decoration: none;">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $('#loginForm').submit(function(e) {
        $('#loginBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Authenticating...');
    });
</script>
@endpush
