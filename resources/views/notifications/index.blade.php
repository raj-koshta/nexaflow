@extends('layouts.master')

@section('title', 'Notification Center')

@push('custom-css')
<style>
    .notification-item {
        transition: all 0.2s ease;
        border-left: 4px solid transparent;
    }
    .notification-item:hover {
        background-color: var(--secondary-bg) !important;
    }
    .notification-unread {
        background-color: rgba(var(--bs-primary-rgb), 0.03);
        border-left-color: var(--bs-primary);
    }
    
    .filter-tab {
        color: var(--text-muted);
        border-bottom: 2px solid transparent;
        padding-bottom: 10px;
        transition: all 0.2s ease;
    }
    .filter-tab:hover {
        color: var(--text-main);
    }
    .filter-tab.active {
        color: var(--bs-primary);
        border-bottom-color: var(--bs-primary);
        font-weight: 600;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <h1 class="h2 fw-bold mb-0">Notification Center</h1>
        <p class="text-muted mb-0">Manage all your alerts and updates.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        @if($unreadCount > 0)
        <button type="button" class="btn btn-outline-primary shadow-sm" id="btnMarkAllRead">
            <i class="bi bi-check2-all me-1"></i> Mark All as Read
        </button>
        @endif
        <form action="{{ route('notifications.test') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary shadow-sm">
                <i class="bi bi-bell-fill me-1"></i> Test Alert
            </button>
        </form>
    </div>
</div>

<div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border); min-height: 500px;">
    <!-- Header / Filters -->
    <div class="card-header bg-transparent p-4 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3" style="border-color: var(--border-color) !important;">
        
        <!-- Tabs -->
        <div class="d-flex gap-4">
            <a href="{{ route('notifications.index', ['tab' => 'all'] + request()->except('page', 'tab')) }}" class="text-decoration-none filter-tab {{ $tab === 'all' ? 'active' : '' }}">
                All Notifications
            </a>
            <a href="{{ route('notifications.index', ['tab' => 'unread'] + request()->except('page', 'tab')) }}" class="text-decoration-none filter-tab {{ $tab === 'unread' ? 'active' : '' }}">
                Unread <span class="badge bg-primary ms-1 rounded-pill">{{ $unreadCount }}</span>
            </a>
            <a href="{{ route('notifications.index', ['tab' => 'read'] + request()->except('page', 'tab')) }}" class="text-decoration-none filter-tab {{ $tab === 'read' ? 'active' : '' }}">
                Read
            </a>
        </div>

        <!-- Search -->
        <form action="{{ route('notifications.index') }}" method="GET" class="d-flex" style="max-width: 300px;">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="input-group">
                <span class="input-group-text bg-transparent" style="border-color: var(--border-color);"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search alerts..." value="{{ request('search') }}" style="background: transparent; border-color: var(--border-color); color: var(--text-main);">
                @if(request('search'))
                    <a href="{{ route('notifications.index', ['tab' => $tab]) }}" class="btn btn-outline-secondary border-start-0" style="border-color: var(--border-color);"><i class="bi bi-x"></i></a>
                @endif
            </div>
        </form>
    </div>

    <!-- Notification List -->
    <div class="card-body p-0">
        @if($notifications->count() > 0)
            <div class="list-group list-group-flush">
                @foreach($notifications as $notification)
                @php
                    $isUnread = is_null($notification->read_at);
                    $data = $notification->data;
                    $icon = $data['icon'] ?? 'bi-bell';
                    $color = $data['color'] ?? 'primary';
                @endphp
                
                <div class="list-group-item p-4 bg-transparent border-bottom notification-item {{ $isUnread ? 'notification-unread' : '' }}" style="border-color: var(--border-color) !important;" id="notif-{{ $notification->id }}">
                    <div class="d-flex gap-3 align-items-start">
                        <!-- Icon -->
                        <div class="bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="width: 48px; height: 48px;">
                            <i class="bi {{ $icon }} fs-5"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0 text-main {{ $isUnread ? '' : 'text-muted' }}">
                                    {{ $data['title'] ?? 'System Alert' }}
                                </h6>
                                <span class="small text-muted" title="{{ $notification->created_at->format('Y-m-d H:i') }}">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mb-2 {{ $isUnread ? 'text-main' : 'text-muted' }}">
                                {{ $data['message'] ?? '' }}
                            </p>
                            
                            <!-- Actions -->
                            <div class="d-flex gap-2 mt-2 align-items-center">
                                @if(!empty($data['url']))
                                    <a href="{{ $data['url'] }}" class="btn btn-sm btn-outline-{{ $color }} rounded-pill px-3">View Details</a>
                                @endif
                                
                                <div class="ms-auto">
                                    @if($isUnread)
                                        <button class="btn btn-sm btn-link text-decoration-none text-muted p-0 me-3 btn-mark-read" data-id="{{ $notification->id }}" title="Mark as Read">
                                            <i class="bi bi-check2"></i> Mark Read
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-link text-decoration-none text-danger p-0 btn-delete" data-id="{{ $notification->id }}" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="p-4 border-top d-flex justify-content-center" style="border-color: var(--border-color) !important;">
                {{ $notifications->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center p-5 my-5">
                <i class="bi bi-bell-slash fs-1 text-muted opacity-50 mb-3 d-block"></i>
                <h5 class="text-muted fw-bold">No Notifications Found</h5>
                <p class="text-muted small">You don't have any {{ request('search') ? 'matching ' : '' }}{{ $tab === 'unread' ? 'unread ' : '' }}notifications.</p>
                @if(request('search') || $tab !== 'all')
                    <a href="{{ route('notifications.index') }}" class="btn btn-outline-secondary mt-2">Clear Filters</a>
                @endif
            </div>
        @endif
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Mark Single as Read
        $('.btn-mark-read').on('click', function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const $btn = $(this);
            const $item = $('#notif-' + id);
            
            $.ajax({
                url: `/notifications/${id}/read`,
                type: 'POST',
                data: { _token: csrfToken },
                success: function(response) {
                    $item.removeClass('notification-unread');
                    $item.find('.fw-bold').addClass('text-muted');
                    $item.find('.text-main').addClass('text-muted').removeClass('text-main');
                    $btn.fadeOut(300, function() { $(this).remove(); });
                    
                    // Update header badge globally
                    fetchNotifications();
                }
            });
        });

        // Delete Single
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            if(!confirm('Are you sure you want to delete this notification?')) return;
            
            const id = $(this).data('id');
            const $item = $('#notif-' + id);
            
            $.ajax({
                url: `/notifications/${id}`,
                type: 'DELETE',
                data: { _token: csrfToken },
                success: function(response) {
                    $item.slideUp(300, function() { 
                        $(this).remove();
                        if($('.notification-item').length === 0) {
                            location.reload();
                        }
                    });
                    fetchNotifications(); // Update header
                }
            });
        });

        // Mark All Read
        $('#btnMarkAllRead').on('click', function() {
            const $btn = $(this);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...').prop('disabled', true);
            
            $.ajax({
                url: '{{ route("notifications.readAll") }}',
                type: 'POST',
                data: { _token: csrfToken },
                success: function(response) {
                    location.reload();
                }
            });
        });
    });
</script>
@endpush