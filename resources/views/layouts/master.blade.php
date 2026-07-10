<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NexaFlow') - Business Automation CRM</title>
    
    <!-- Initialize Theme before rendering body to prevent flickering -->
    <script>
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
    
    @include('layouts.styles')
    @stack('custom-css')
</head>
<body>

    @include('layouts.header')

    <div class="container-fluid p-0">
        <div class="d-flex w-100">
            @auth
                @include('layouts.sidebar')
                <main class="content-wrapper w-100 main-content pt-3 px-4">
                    @yield('content')
                </main>
            @else
                <main class="content-wrapper w-100 pt-3 px-4">
                    @yield('content')
                </main>
            @endauth
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;"></div>

    @stack('modals')

    @include('layouts.scripts')
    <script>
        $(document).ready(function() {
            // Toast helper function
            window.showToast = function(title, message, type = 'success') {
                const id = 'toast-' + Date.now();
                const icon = type === 'success' ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger';
                
                const toastHtml = `
                    <div id="${id}" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header" style="background: var(--secondary-bg); border-color: var(--border-color); color: var(--text-main);">
                            <i class="bi ${icon} me-2"></i>
                            <strong class="me-auto">${title}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close" style="filter: invert(var(--bs-theme) === 'dark' ? 1 : 0);"></button>
                        </div>
                        <div class="toast-body" style="background: var(--primary-bg); color: var(--text-main);">
                            ${message}
                        </div>
                    </div>
                `;
                
                $('.toast-container').append(toastHtml);
                const toast = new bootstrap.Toast(document.getElementById(id), { delay: 3000 });
                toast.show();
                
                $('#' + id).on('hidden.bs.toast', function () {
                    $(this).remove();
                });
            };

            // Mobile sidebar toggler
            $('#sidebarToggle').on('click', function(e) {
                e.stopPropagation();
                $('#sidebarMenu').toggleClass('show');
            });

            // Close sidebar when clicking outside on mobile
            $(document).on('click', function(e) {
                if ($(window).width() < 992) {
                    if (!$(e.target).closest('#sidebarMenu, #sidebarToggle').length) {
                        $('#sidebarMenu').removeClass('show');
                    }
                }
            });
        });
    </script>
    
    @stack('custom-scripts')
</body>
</html>
