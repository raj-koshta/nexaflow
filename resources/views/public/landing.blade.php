@extends('layouts.public')

@section('title', 'Business Automation CRM')

@section('content')
<!-- Hero Section -->
<section class="hero-section text-center position-relative overflow-hidden">
    <div class="container position-relative z-1">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2 mb-4 border border-primary border-opacity-25">
                    <i class="bi bi-stars me-1"></i> NexaFlow 2.0 is Here!
                </span>
                <h1 class="display-3 fw-bold mb-4" style="letter-spacing: -1px;">
                    Automate Your Business with <span class="text-accent">NexaFlow</span>
                </h1>
                <p class="lead text-muted mb-5 px-md-5">
                    The ultimate CRM platform combining client management, project tracking, and AI-powered insights to help your business grow faster than ever.
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm">
                        Start for Free
                    </a>
                    <a href="{{ route('public.about') }}" class="btn btn-outline-light btn-lg px-5 rounded-pill">
                        Learn More
                    </a>
                </div>
                
                <div class="mt-5 pt-4 text-muted small text-uppercase letter-spacing-1">
                    <p>Trusted by innovative teams worldwide</p>
                    <div class="d-flex justify-content-center gap-4 opacity-50 fs-4">
                        <i class="bi bi-microsoft"></i>
                        <i class="bi bi-google"></i>
                        <i class="bi bi-apple"></i>
                        <i class="bi bi-spotify"></i>
                        <i class="bi bi-slack"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 my-5">
    <div class="container">
        <div class="text-center mb-5 pb-3">
            <h2 class="fw-bold h1">Everything you need to succeed</h2>
            <p class="text-muted lead">Powerful features designed to streamline your workflow.</p>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card p-4 h-100 text-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-people fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Client Management</h4>
                    <p class="text-muted">Keep all your client interactions, leads, and contacts organized in one secure place.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 h-100 text-center">
                    <div class="bg-accent bg-opacity-10 text-accent rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-kanban fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">Project Tracking</h4>
                    <p class="text-muted">Manage projects with interactive Kanban boards, task assignments, and progress tracking.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 h-100 text-center">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                        <i class="bi bi-robot fs-1"></i>
                    </div>
                    <h4 class="fw-bold mb-3">AI Assistant</h4>
                    <p class="text-muted">Leverage the power of Gemini AI to summarize tickets, generate emails, and get business insights.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="py-5 mb-5">
    <div class="container">
        <div class="bg-gradient-primary rounded-4 p-5 text-center text-white position-relative overflow-hidden shadow-lg">
            <div class="position-relative z-1 py-4">
                <h2 class="fw-bold h1 mb-3">Ready to transform your business?</h2>
                <p class="lead mb-4 opacity-75">Join thousands of companies using NexaFlow to scale their operations.</p>
                <a href="{{ route('register') }}" class="btn btn-light text-primary btn-lg px-5 rounded-pill fw-bold shadow-sm">
                    Create Your Free Account
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
