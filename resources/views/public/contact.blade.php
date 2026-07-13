@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5">
            <h1 class="display-5 fw-bold mb-3">Get in Touch</h1>
            <p class="lead text-muted">Have a question or need help? Our team is here for you.</p>
        </div>
    </div>
    
    <div class="row g-5 justify-content-center">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border) !important; border-radius: 16px;">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Send us a message</h4>
                    <form>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase letter-spacing-1">Your Name</label>
                            <input type="text" class="form-control" placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase letter-spacing-1">Email Address</label>
                            <input type="email" class="form-control" placeholder="john@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small text-uppercase letter-spacing-1">Subject</label>
                            <input type="text" class="form-control" placeholder="How can we help?">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small text-uppercase letter-spacing-1">Message</label>
                            <textarea class="form-control" rows="4" placeholder="Tell us more..."></textarea>
                        </div>
                        <button type="button" class="btn btn-primary w-100 rounded-pill">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                <div class="d-flex align-items-start">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-envelope-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Email</h5>
                        <p class="text-muted mb-0">{{ setting('support_email', 'support@nexaflow.com') }}</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-start">
                    <div class="bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-telephone-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Phone</h5>
                        <p class="text-muted mb-0">{{ setting('company_phone', '+1 (555) 123-4567') }}</p>
                    </div>
                </div>
                
                <div class="d-flex align-items-start">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Office</h5>
                        <p class="text-muted mb-0">{!! nl2br(e(setting('company_address', "123 Business Avenue\nTech District\nSan Francisco, CA 94107"))) !!}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
