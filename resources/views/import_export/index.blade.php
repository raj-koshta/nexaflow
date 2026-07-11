@extends('layouts.master')

@section('title', 'Import & Export')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">Import & Export</h1>
        <p class="text-muted">Bulk manage your data using CSV files.</p>
    </div>
</div>

<div class="row">
    <!-- Import Section -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold"><i class="bi bi-cloud-upload text-primary me-2"></i> Import Data</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Upload a CSV file to bulk import records into the system. Please ensure your file matches the required template format.</p>
                
                <form action="{{ route('import-export.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Select Entity</label>
                        <select name="entity" id="importEntity" class="form-select" required>
                            <option value="">-- Choose Module --</option>
                            <option value="clients">Clients</option>
                            <option value="leads">Leads</option>
                            <option value="projects">Projects</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Upload CSV File</label>
                        <input type="file" name="file" class="form-control" accept=".csv" required>
                        <div class="form-text">Max file size: 10MB.</div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-upload"></i> Start Import
                        </button>
                        
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="downloadTemplateBtn">
                            <i class="bi bi-download"></i> Download Template
                        </button>
                    </div>
                </form>

                <!-- Hidden form for downloading template -->
                <form id="templateForm" action="{{ route('import-export.template') }}" method="POST" class="d-none">
                    @csrf
                    <input type="hidden" name="entity" id="templateEntity">
                </form>
            </div>
        </div>
    </div>

    <!-- Export Section -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-transparent border-bottom-0 pt-4 pb-0">
                <h5 class="fw-bold"><i class="bi bi-cloud-download text-success me-2"></i> Export Data</h5>
            </div>
            <div class="card-body">
                <p class="text-muted small mb-4">Export your existing records to a CSV file for backup or external use.</p>
                
                <form action="{{ route('import-export.export') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label">Select Entity to Export</label>
                        <select name="entity" class="form-select" required>
                            <option value="">-- Choose Module --</option>
                            <option value="clients">Clients</option>
                            <option value="leads">Leads</option>
                            <option value="projects">Projects</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-download"></i> Export Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        $('#downloadTemplateBtn').on('click', function() {
            let entity = $('#importEntity').val();
            if (!entity) {
                alert('Please select an entity first to download its template.');
                return;
            }
            $('#templateEntity').val(entity);
            $('#templateForm').submit();
        });
    });
</script>
@endpush
