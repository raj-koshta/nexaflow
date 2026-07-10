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

@auth
// Notifications
$(document).ready(function() {
    function fetchNotifications() {
        $.ajax({
            url: '{{ route('notifications.index') }}',
            type: 'GET',
            success: function(res) {
                const list = $('.notification-list');
                const badge = $('#notificationBadge');
                
                list.empty();
                
                if (res.unread_count > 0) {
                    badge.removeClass('d-none');
                } else {
                    badge.addClass('d-none');
                }
                
                if (res.unread.length === 0 && res.read.length === 0) {
                    list.append('<div class="text-center p-4 text-muted small" id="noNotificationsMsg">No new notifications</div>');
                    return;
                }
                
                res.unread.forEach(function(notif) {
                    const data = notif.data;
                    const time = new Date(notif.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    list.append(`
                        <a href="${data.link || '#'}" class="dropdown-item p-3 border-bottom text-wrap notif-item unread" data-id="${notif.id}" style="background: rgba(139, 92, 246, 0.05); border-color: var(--border-color) !important;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-${data.type} bg-opacity-10 text-${data.type} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="bi ${data.icon} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-main" style="font-size: 0.9rem;">${data.title}</h6>
                                    <p class="mb-1 text-muted small" style="font-size: 0.8rem; line-height: 1.4;">${data.message}</p>
                                    <small class="text-muted" style="font-size: 0.7rem;">${time}</small>
                                </div>
                            </div>
                        </a>
                    `);
                });
                
                res.read.forEach(function(notif) {
                    const data = notif.data;
                    const time = new Date(notif.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    list.append(`
                        <a href="${data.link || '#'}" class="dropdown-item p-3 border-bottom text-wrap notif-item" data-id="${notif.id}" style="border-color: var(--border-color) !important; opacity: 0.7;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                    <i class="bi ${data.icon} fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-medium text-main" style="font-size: 0.9rem;">${data.title}</h6>
                                    <p class="mb-1 text-muted small" style="font-size: 0.8rem; line-height: 1.4;">${data.message}</p>
                                    <small class="text-muted" style="font-size: 0.7rem;">${time}</small>
                                </div>
                            </div>
                        </a>
                    `);
                });
            }
        });
    }

    // Initial fetch
    fetchNotifications();

    // Mark as read on click
    $(document).on('click', '.notif-item.unread', function(e) {
        if (!$(e.target).closest('a').attr('href') || $(e.target).closest('a').attr('href') === '#') {
            e.preventDefault();
        }
        
        const id = $(this).data('id');
        const item = $(this);
        
        $.ajax({
            url: '/notifications/' + id + '/read',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                item.removeClass('unread');
                item.css('background', 'transparent');
                item.css('opacity', '0.7');
                item.find('.bg-opacity-10').removeClass(function (index, className) {
                    return (className.match (/(^|\s)bg-\S+/g) || []).join(' ');
                }).addClass('bg-secondary');
                item.find('.text-primary, .text-success, .text-warning, .text-danger, .text-info').removeClass(function (index, className) {
                    return (className.match (/(^|\s)text-\S+/g) || []).join(' ');
                }).addClass('text-secondary');
                
                const currentCount = parseInt($('#notificationBadge').text()) || 1;
                if (currentCount <= 1) {
                    $('#notificationBadge').addClass('d-none');
                }
            }
        });
    });

    // Mark all as read
    $('#markAllReadBtn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        $.ajax({
            url: '{{ route('notifications.readAll') }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function() {
                fetchNotifications();
            }
        });
    });
});

// Global Search
$(document).ready(function() {
    let searchTimeout = null;
    const searchInput = $('#globalSearchInput');
    const searchResults = $('#globalSearchResults');

    // Close dropdown on click outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#globalSearchContainer').length) {
            searchResults.removeClass('show');
        }
    });

    // Handle input
    searchInput.on('input', function() {
        const query = $(this).val();

        if (searchTimeout) clearTimeout(searchTimeout);

        if (query.length < 3) {
            searchResults.removeClass('show');
            return;
        }

        searchTimeout = setTimeout(() => {
            searchResults.addClass('show');
            searchResults.html('<div class="text-center p-4"><span class="spinner-border spinner-border-sm text-primary"></span><span class="ms-2 text-muted small">Searching...</span></div>');
            
            $.ajax({
                url: '{{ route('global.search') }}',
                type: 'GET',
                data: { q: query },
                success: function(res) {
                    searchResults.empty();

                    if (Object.keys(res).length === 0) {
                        searchResults.html('<div class="text-center p-4 text-muted small">No results found for "'+query+'"</div>');
                        return;
                    }

                    for (const [type, items] of Object.entries(res)) {
                        let sectionHtml = `<h6 class="dropdown-header text-uppercase fw-bold mt-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">${type}s</h6>`;
                        searchResults.append(sectionHtml);

                        items.forEach(item => {
                            searchResults.append(`
                                <a class="dropdown-item d-flex align-items-center p-3 border-bottom" href="${item.url}" style="border-color: rgba(255,255,255,0.05) !important; white-space: normal;">
                                    <div class="bg-${item.color} bg-opacity-10 text-${item.color} rounded d-flex align-items-center justify-content-center me-3 flex-shrink-0" style="width: 36px; height: 36px;">
                                        <i class="bi ${item.icon} fs-5"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h6 class="mb-0 text-main fw-bold text-truncate" style="font-size: 0.9rem;">${item.title}</h6>
                                        <small class="text-muted d-block text-truncate" style="font-size: 0.8rem;">${item.subtitle}</small>
                                    </div>
                                </a>
                            `);
                        });
                    }
                },
                error: function() {
                    searchResults.html('<div class="text-center p-4 text-danger small">An error occurred while searching.</div>');
                }
            });
        }, 300);
    });

    // Handle focus
    searchInput.on('focus', function() {
        if ($(this).val().length >= 3) {
            searchResults.addClass('show');
        }
    });
});
@endauth
</script>
