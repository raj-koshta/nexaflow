@extends('layouts.public')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5 my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center mb-5 reveal">
            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-4 border border-primary border-opacity-25">
                <i class="bi bi-headset me-1"></i> We're Here to Help
            </span>
            <h1 class="display-5 fw-bold mb-3">Get in Touch</h1>
            <p class="lead text-muted">Have a question or need help? Our team is here for you.</p>
        </div>
    </div>
    
    <div class="row g-5 justify-content-center">
        <div class="col-lg-5 reveal delay-100">
            <div class="card shadow-lg border-0 h-100 feature-card position-relative overflow-hidden" style="background: var(--public-card-bg) !important; border-radius: 16px;">
                <div class="position-absolute top-0 start-0 w-100 h-1" style="background: linear-gradient(90deg, var(--primary), var(--accent)); height: 4px;"></div>
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Send us a message</h4>
                    <form>
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control bg-transparent" id="contactName" placeholder="John Doe">
                            <label for="contactName" class="text-muted">Your Name</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <input type="email" class="form-control bg-transparent" id="contactEmail" placeholder="name@example.com">
                            <label for="contactEmail" class="text-muted">Email Address</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <input type="text" class="form-control bg-transparent" id="contactSubject" placeholder="Subject">
                            <label for="contactSubject" class="text-muted">Subject</label>
                        </div>
                        <div class="mb-4 form-floating">
                            <textarea class="form-control bg-transparent" id="contactMessage" placeholder="Tell us more..." style="height: 120px"></textarea>
                            <label for="contactMessage" class="text-muted">Message</label>
                        </div>
                        <button type="button" class="btn btn-primary text-white w-100 py-3 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, var(--primary), var(--accent)); border: none;">
                            Send Message <i class="bi bi-send ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4 reveal delay-200">
            <div class="d-flex flex-column gap-4">
                <div class="card border-0 feature-card p-4 rounded-4" style="background: var(--public-card-bg) !important; border: 1px solid var(--public-glass-border) !important;">
                    <div class="d-flex align-items-start">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-envelope-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Email</h5>
                            <p class="text-muted mb-0">{{ setting('support_email', 'support@nexaflow.com') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-transparent border-0 feature-card p-4 rounded-4" style="border: 1px solid var(--border-color) !important;">
                    <div class="d-flex align-items-start">
                        <div class="bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-telephone-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Phone</h5>
                            <p class="text-muted mb-0">{{ setting('company_phone', '+1 (555) 123-4567') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card bg-transparent border-0 feature-card p-4 rounded-4" style="border: 1px solid rgba(255,255,255,0.05) !important;">
                    <div class="d-flex align-items-start">
                        <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-4 flex-shrink-0 shadow-sm" style="width: 60px; height: 60px;">
                            <i class="bi bi-geo-alt-fill fs-4"></i>
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
</div>
@endsection
