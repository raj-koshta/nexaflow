@extends('layouts.master')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 64px);">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="background: linear-gradient(to right, var(--text-main), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Join NexaFlow</h2>
            <p class="text-muted">Create an account to start managing your business</p>
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

                <form method="POST" action="{{ route('register.post') }}" id="registerForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="name" class="form-label">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control border-start-0" id="name" name="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-lock"></i></span>
                                <input type="password" class="form-control border-start-0" id="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="password_confirmation" class="form-label">Confirm</label>
                            <div class="input-group">
                                <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-shield-lock"></i></span>
                                <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5 mt-2" id="registerBtn">
                        Create Account
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Already have an account? <a href="{{ route('login') }}" class="fw-medium" style="color: var(--accent); text-decoration: none;">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $('#registerForm').submit(function(e) {
        $('#registerBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Creating account...');
    });
</script>
@endpush
