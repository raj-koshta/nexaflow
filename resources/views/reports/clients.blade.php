@extends('layouts.master')

@section('title', 'Client Report')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('reports.index') }}" class="text-decoration-none">Reports</a></li>
                <li class="breadcrumb-item active" aria-current="page">Client Report</li>
            </ol>
        </nav>
        <h1 class="h2 fw-bold mb-0">Client Report</h1>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-outline-secondary shadow-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i> Print
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-12">
        <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-body p-4 d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="text-muted text-uppercase small letter-spacing-1 mb-1">Total Active Clients</h5>
                    <h2 class="fw-bold text-primary mb-0">{{ $totalClients }}</h2>
                </div>
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                    <i class="bi bi-people-fill fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0">Clients by Industry</h6>
            </div>
            <div class="card-body">
                @if($clientsByIndustry->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                            <tbody>
                                @foreach($clientsByIndustry as $stat)
                                <tr>
                                    <td class="border-0">{{ $stat->industry }}</td>
                                    <td class="border-0 text-end fw-bold">{{ $stat->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center my-4">No industry data available.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100" style="background: var(--card-bg); border: var(--glass-border);">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h6 class="fw-bold mb-0">Clients by Status</h6>
            </div>
            <div class="card-body">
                @if($clientsByStatus->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                            <tbody>
                                @foreach($clientsByStatus as $stat)
                                <tr>
                                    <td class="border-0">
                                        @php
                                            $sColor = match($stat->status) {
                                                'Active' => 'success', 'Inactive' => 'secondary', 'Churned' => 'danger', default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $sColor }} bg-opacity-10 text-{{ $sColor }} rounded-pill px-2">{{ $stat->status }}</span>
                                    </td>
                                    <td class="border-0 text-end fw-bold">{{ $stat->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center my-4">No status data available.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-header bg-transparent border-bottom pt-4 pb-3" style="border-color: var(--border-color) !important;">
        <h6 class="fw-bold mb-0">Recently Added Clients</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                <thead style="background: rgba(255,255,255,0.02);">
                    <tr>
                        <th class="border-bottom-0 text-uppercase text-muted ps-4" style="font-size: 0.75rem; letter-spacing: 1px;">Company</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Contact</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Industry</th>
                        <th class="border-bottom-0 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 1px;">Joined</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentClients as $client)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td class="py-3 ps-4 fw-medium">{{ $client->company_name }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span>{{ $client->first_name }} {{ $client->last_name }}</span>
                                <small class="text-muted">{{ $client->email }}</small>
                            </div>
                        </td>
                        <td>{{ $client->industry ?? 'N/A' }}</td>
                        <td>{{ $client->created_at->format('M d, Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">No clients found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
