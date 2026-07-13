@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<div class="container py-5 my-5">
    <div class="row align-items-center gy-5">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold mb-4">About <span class="text-accent">NexaFlow</span></h1>
            <p class="lead text-muted mb-4">
                We're on a mission to simplify business automation. NexaFlow was built with the vision that powerful software shouldn't be complicated to use.
            </p>
            <p class="text-muted mb-4">
                Founded by a team of passionate developers and business experts, we understand the pain points of managing clients, projects, and daily tasks across disjointed systems.
            </p>
            <p class="text-muted">
                That's why we created a unified, intuitive platform augmented with cutting-edge AI technology to help you focus on what really matters: growing your business.
            </p>
            
            <div class="d-flex gap-4 mt-5">
                <div>
                    <h3 class="fw-bold text-primary mb-1">10k+</h3>
                    <div class="text-muted small text-uppercase letter-spacing-1">Active Users</div>
                </div>
                <div class="vr bg-secondary opacity-25"></div>
                <div>
                    <h3 class="fw-bold text-primary mb-1">99.9%</h3>
                    <div class="text-muted small text-uppercase letter-spacing-1">Uptime</div>
                </div>
                <div class="vr bg-secondary opacity-25"></div>
                <div>
                    <h3 class="fw-bold text-primary mb-1">24/7</h3>
                    <div class="text-muted small text-uppercase letter-spacing-1">Support</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="position-relative">
                <div class="bg-gradient-primary rounded-4 position-absolute w-100 h-100 top-0 start-0 opacity-25 translate-middle-y ms-4 mt-4" style="z-index: -1; transform: rotate(3deg);"></div>
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Our Team" class="img-fluid rounded-4 shadow-lg border border-secondary border-opacity-25">
            </div>
        </div>
    </div>
</div>
@endsection
