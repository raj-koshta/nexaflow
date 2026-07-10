@extends('layouts.master')

@section('title', 'File Manager')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">File Manager</h1>
        <p class="text-muted">Manage all uploaded files and documents across the system.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="d-flex align-items-center bg-primary bg-opacity-10 text-primary px-3 py-2 rounded">
            <i class="bi bi-hdd me-2"></i>
            <strong>Total Storage: {{ number_format($totalStorageBytes / 1048576, 2) }} MB</strong>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <form action="{{ route('file-manager.index') }}" method="GET" class="row g-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-start-0" name="search" placeholder="Search by file name..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                    <option value="document" {{ request('type') == 'document' ? 'selected' : '' }}>Documents (PDF, Word)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                @if(request()->has('search') || request()->has('type'))
                    <a href="{{ route('file-manager.index') }}" class="btn btn-outline-secondary w-100">Clear</a>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Files Grid -->
<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4 mb-4">
    @forelse($documents as $doc)
        @php
            $icon = 'bi-file-earmark-text';
            $color = 'secondary';
            $typeLower = strtolower($doc->mime_type);
            
            if (str_contains($typeLower, 'image')) {
                $icon = 'bi-file-earmark-image';
                $color = 'primary';
            } elseif (str_contains($typeLower, 'pdf')) {
                $icon = 'bi-file-earmark-pdf';
                $color = 'danger';
            } elseif (str_contains($typeLower, 'word') || str_contains($typeLower, 'document')) {
                $icon = 'bi-file-earmark-word';
                $color = 'info';
            } elseif (str_contains($typeLower, 'zip') || str_contains($typeLower, 'compressed')) {
                $icon = 'bi-file-earmark-zip';
                $color = 'warning';
            }
        @endphp
        <div class="col">
            <div class="card h-100 shadow-sm border-0 file-card" style="transition: transform 0.2s;">
                <div class="card-body text-center position-relative pb-0">
                    <div class="mb-3 mt-2 text-{{ $color }}">
                        <i class="bi {{ $icon }}" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="card-title text-truncate mb-1" title="{{ $doc->file_name }}">{{ $doc->file_name }}</h6>
                    <p class="text-muted small mb-2">{{ number_format($doc->size / 1024, 2) }} KB</p>
                    
                    <div class="small text-muted mb-3 d-flex flex-column gap-1">
                        @if($doc->client_id)
                            <span><i class="bi bi-building me-1"></i> Client: {{ $doc->client->company_name ?? 'Unknown' }}</span>
                        @elseif($doc->lead_id)
                            <span><i class="bi bi-funnel me-1"></i> Lead: {{ $doc->lead->name ?? 'Unknown' }}</span>
                        @else
                            <span><i class="bi bi-tag me-1"></i> General System File</span>
                        @endif
                        <span><i class="bi bi-person me-1"></i> {{ $doc->creator->name ?? 'System' }}</span>
                        <span><i class="bi bi-calendar me-1"></i> {{ $doc->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 d-flex justify-content-between p-3 pt-0">
                    <a href="{{ route('file-manager.download', $doc->id) }}" class="btn btn-sm btn-outline-primary flex-grow-1 me-2">
                        <i class="bi bi-download"></i> Download
                    </a>
                    <form action="{{ route('file-manager.destroy', $doc->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this file? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="bi bi-folder2-open display-1 text-muted mb-3 d-block opacity-50"></i>
                    <h4>No files found</h4>
                    <p class="text-muted">There are no files matching your criteria or uploaded to the system yet.</p>
                </div>
            </div>
        </div>
    @endforelse
</div>

@if($documents->hasPages())
    <div class="d-flex justify-content-end mb-5">
        {{ $documents->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
@endif

@endsection

@push('custom-css')
<style>
    .file-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush
