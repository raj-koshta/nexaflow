@extends('layouts.master')

@section('title', 'Forgot Password')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 64px);">
    <div class="col-md-6 col-lg-4">
        <div class="text-center mb-4">
            <h2 class="fw-bold" style="background: linear-gradient(to right, var(--text-main), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Reset Password</h2>
            <p class="text-muted">Enter your email and we'll send you a reset link.</p>
        </div>
        <div class="card p-2">
            <div class="card-body p-4">

                @if (session('status'))
                    <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); color: #28a745;">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.2); color: #ff6b6b;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text border-0" style="background: rgba(255,255,255,0.05); color: var(--text-muted);"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control border-start-0" id="email" name="email" value="{{ old('email') }}" placeholder="name@company.com" required autofocus>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-5" id="resetBtn">
                        Send Reset Link
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0 text-muted">Remembered your password? <a href="{{ route('login') }}" class="fw-medium" style="color: var(--accent); text-decoration: none;">Sign in</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $('#forgotPasswordForm').submit(function(e) {
        $('#resetBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending link...');
    });
</script>
@endpush
