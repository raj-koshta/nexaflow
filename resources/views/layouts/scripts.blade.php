<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Custom Scripts placeholder -->
@stack('custom-scripts')
<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<!-- Global Script -->
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Global confirmation wrapper using SweetAlert2
    window.confirmAction = function(title, text, confirmCallback) {
        const isDark = $('html').attr('data-bs-theme') === 'dark';
        
        Swal.fire({
            title: title || 'Are you sure?',
            text: text || "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: 'var(--accent)',
            cancelButtonColor: 'var(--text-muted)',
            confirmButtonText: 'Yes, proceed!',
            background: isDark ? 'var(--secondary-bg)' : '#fff',
            color: 'var(--text-main)',
            customClass: {
                popup: 'border border-secondary border-opacity-10 shadow-lg rounded-4'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                confirmCallback();
            }
        });
    };

    // Global toast function
    function showToast(title, message, type = 'success') {
        let bgClass = type === 'success' ? 'bg-success' : 'bg-danger';
        let toastHtml = `
            <div class="toast align-items-center text-white ${bgClass} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <strong>${title}</strong><br>${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;
        let $toastContainer = $('#toast-container');
        if($toastContainer.length === 0) {
            $('body').append('<div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>');
            $toastContainer = $('#toast-container');
        }
        let $toastElement = $(toastHtml);
        $toastContainer.append($toastElement);
        let bsToast = new bootstrap.Toast($toastElement[0]);
        bsToast.show();
        
        $toastElement.on('hidden.bs.toast', function () {
            $(this).remove();
        });
    }

    // Theme Switcher Logic
    $(document).ready(function() {
        const currentTheme = document.documentElement.getAttribute('data-bs-theme');
        const themeIcon = $('#theme-icon');
        
        if (currentTheme === 'light') {
            themeIcon.removeClass('bi-moon-stars-fill').addClass('bi-brightness-high-fill');
        }

        $('#theme-toggle').click(function() {
            const current = document.documentElement.getAttribute('data-bs-theme');
            const targetTheme = current === 'dark' ? 'light' : 'dark';
            
            document.documentElement.setAttribute('data-bs-theme', targetTheme);
            localStorage.setItem('theme', targetTheme);

            if (targetTheme === 'light') {
                themeIcon.removeClass('bi-moon-stars-fill').addClass('bi-brightness-high-fill');
            } else {
                themeIcon.removeClass('bi-brightness-high-fill').addClass('bi-moon-stars-fill');
            }
        });
    });
</script>
