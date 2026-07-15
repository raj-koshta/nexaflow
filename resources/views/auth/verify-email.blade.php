@extends('layouts.master')

@section('title', 'Verify Email')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: calc(100vh - 64px);">
    <div class="col-md-6 col-lg-5">
        <div class="text-center mb-4">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="bi bi-envelope-check fs-1"></i>
            </div>
            <h2 class="fw-bold" style="background: linear-gradient(to right, var(--text-main), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Verify your email address</h2>
            <p class="text-muted mt-3 fs-5">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
            </p>
            <p class="text-muted mb-0">If you didn't receive the email, we will gladly send you another.</p>
        </div>
        
        <div class="card p-2 border-0 shadow-sm" style="background: var(--card-bg);">
            <div class="card-body p-4 text-center">

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success mb-4" style="background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.2); color: #28a745;">
                        A new verification link has been sent to the email address you provided during registration.
                    </div>
                @endif

                <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center gap-3">
                    <form method="POST" action="{{ route('verification.send') }}" id="resendForm" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100 py-2" id="resendBtn">
                            Resend Verification Email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="w-100">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary w-100 py-2">
                            Log Out
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $('#resendForm').submit(function(e) {
        $('#resendBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...');
    });
</script>
@endpush
