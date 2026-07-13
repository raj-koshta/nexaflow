@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<div class="container py-5 my-5">
    <div class="row align-items-center gy-5">
        <div class="col-lg-6 reveal">
            <span class="badge bg-accent bg-opacity-10 text-accent rounded-pill px-3 py-2 mb-4 border border-accent border-opacity-25">
                <i class="bi bi-info-circle me-1"></i> Our Story
            </span>
            <h1 class="display-4 fw-bold mb-4">About <span class="text-accent" style="text-shadow: 0 5px 15px rgba(236, 72, 153, 0.3);">NexaFlow</span></h1>
            <p class="lead text-muted mb-4">
                We're on a mission to simplify business automation. NexaFlow was built with the vision that powerful software shouldn't be complicated to use.
            </p>
            <p class="text-muted mb-4">
                Founded by a team of passionate developers and business experts, we understand the pain points of managing clients, projects, and daily tasks across disjointed systems.
            </p>
            <p class="text-muted">
                That's why we created a unified, intuitive platform augmented with cutting-edge AI technology to help you focus on what really matters: growing your business.
            </p>
            
            <div class="d-flex gap-4 mt-5 reveal delay-100" id="stats-container">
                <div>
                    <h3 class="fw-bold text-primary mb-1 d-flex align-items-center"><span class="counter" data-target="10">0</span>k+</h3>
                    <div class="text-muted small text-uppercase letter-spacing-1">Active Users</div>
                </div>
                <div class="vr bg-secondary opacity-25"></div>
                <div>
                    <h3 class="fw-bold text-primary mb-1 d-flex align-items-center"><span class="counter" data-target="99">0</span>.9%</h3>
                    <div class="text-muted small text-uppercase letter-spacing-1">Uptime</div>
                </div>
                <div class="vr bg-secondary opacity-25"></div>
                <div>
                    <h3 class="fw-bold text-primary mb-1 d-flex align-items-center"><span class="counter" data-target="24">0</span>/7</h3>
                    <div class="text-muted small text-uppercase letter-spacing-1">Support</div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 reveal delay-200">
            <div class="position-relative">
                <div class="bg-gradient-primary rounded-4 position-absolute w-100 h-100 top-0 start-0 opacity-25 translate-middle-y ms-4 mt-4" style="z-index: -1; transform: rotate(3deg); filter: blur(20px);"></div>
                <div class="bg-gradient-accent rounded-4 position-absolute w-100 h-100 top-0 start-0 opacity-20 translate-middle-x mt-4" style="z-index: -1; transform: rotate(-2deg); filter: blur(30px);"></div>
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" alt="Our Team" class="img-fluid rounded-4 shadow-lg" style="border: 1px solid var(--border-color); transform: perspective(1000px) rotateY(-5deg); transition: transform 0.5s ease;" onmouseover="this.style.transform='perspective(1000px) rotateY(0deg)'" onmouseout="this.style.transform='perspective(1000px) rotateY(-5deg)'">
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        const animateCounters = () => {
            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-target');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 15);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });
        };

        // Create an observer but fallback if IntersectionObserver fails or is unavailable
        if (typeof IntersectionObserver !== 'undefined') {
            const statsObserver = new IntersectionObserver((entries) => {
                if(entries[0].isIntersecting) {
                    animateCounters();
                    statsObserver.disconnect();
                }
            });

            const statsContainer = document.getElementById('stats-container');
            if(statsContainer) {
                statsObserver.observe(statsContainer);
            }
        } else {
            animateCounters(); // Fallback
        }
    });
</script>
@endsection
