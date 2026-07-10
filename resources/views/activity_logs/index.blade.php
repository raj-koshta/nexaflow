@extends('layouts.master')

@section('title', 'System Activity Logs')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">Activity Logs</h1>
        <p class="text-muted">Monitor system events and user actions.</p>
    </div>
</div>

<!-- Search & Filters -->
<div class="card shadow-sm border-0 mb-4" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-body">
        <form action="{{ route('activity-logs.index') }}" method="GET" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" placeholder="Search by user, action, or description..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Search</button>
            </div>
            @if(request()->has('search'))
            <div class="col-md-2">
                <a href="{{ route('activity-logs.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
            @endif
        </form>
    </div>
</div>

<!-- Logs Table -->
<div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light bg-opacity-10">
                    <tr>
                        <th class="border-0 ps-4 text-muted font-monospace small" style="letter-spacing: 0.5px;">TIMESTAMP</th>
                        <th class="border-0 text-muted font-monospace small" style="letter-spacing: 0.5px;">USER</th>
                        <th class="border-0 text-muted font-monospace small" style="letter-spacing: 0.5px;">ACTION</th>
                        <th class="border-0 text-muted font-monospace small" style="letter-spacing: 0.5px;">DESCRIPTION</th>
                        <th class="border-0 text-muted font-monospace small" style="letter-spacing: 0.5px;">IP ADDRESS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="ps-4">
                            <span class="d-block" style="font-size: 0.85rem;">{{ $log->created_at->format('M d, Y') }}</span>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            @if($log->user)
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                        {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-main" style="font-size: 0.9rem;">{{ $log->user->name }}</h6>
                                        <small class="text-muted" style="font-size: 0.75rem;">{{ $log->user->email }}</small>
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-secondary">System</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $badgeColor = 'secondary';
                                $actionLower = strtolower($log->action);
                                if (str_contains($actionLower, 'create')) $badgeColor = 'success';
                                elseif (str_contains($actionLower, 'update') || str_contains($actionLower, 'edit')) $badgeColor = 'warning';
                                elseif (str_contains($actionLower, 'delete') || str_contains($actionLower, 'remove')) $badgeColor = 'danger';
                                elseif (str_contains($actionLower, 'login')) $badgeColor = 'info';
                            @endphp
                            <span class="badge bg-{{ $badgeColor }} bg-opacity-10 text-{{ $badgeColor }} border border-{{ $badgeColor }} rounded-pill px-2">
                                {{ strtoupper($log->action) }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted" style="font-size: 0.85rem;">
                                {{ $log->description ?: '-' }}
                            </span>
                        </td>
                        <td>
                            <code class="text-muted bg-dark bg-opacity-10 px-2 py-1 rounded" style="font-size: 0.8rem;">
                                {{ $log->ip_address ?: 'Unknown' }}
                            </code>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-clock-history fs-1 d-block mb-3 opacity-50"></i>
                            <h5 class="fw-normal">No activity logs found.</h5>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($logs->hasPages())
    <div class="card-footer border-0 bg-transparent pt-4 pb-3 pe-4 d-flex justify-content-end">
        {{ $logs->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
