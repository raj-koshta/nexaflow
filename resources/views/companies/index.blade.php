@extends('layouts.master')

@section('title', 'Company Management')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <div>
        <h1 class="h2 fw-bold mb-0">Company Management</h1>
        <p class="text-muted">Manage companies, branches, or subsidiaries.</p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-primary" onclick="openCompanyModal()">
            <i class="bi bi-building me-1"></i> Create Company
        </button>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Skeleton Loading -->
        @include('layouts.skeleton', ['type' => 'table', 'rows' => 5])
        
        <!-- Table Content -->
        <div class="table-responsive" id="companiesTableContainer" style="display: none;">
            <!-- Rendered via AJAX -->
        </div>
    </div>
</div>

@include('companies.modal')

@endsection

@push('custom-scripts')
<script>
    $(document).ready(function() {
        loadCompanies();

        $('#companyForm').off('submit').on('submit', function(e) {
            e.preventDefault();
            const $btn = $('#saveCompanyBtn');
            const id = $('#companyId').val();
            
            // Because we might upload a file, we must use FormData
            const formData = new FormData(this);
            const method = id ? 'POST' : 'POST'; 
            if(id) {
                formData.append('_method', 'PUT'); // Laravel form spoofing for PUT
            }
            
            const url = id ? `/companies/${id}` : '{{ route("companies.store") }}';

            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(res) {
                    $('#companyModal').modal('hide');
                    showToast('Success', res.message, 'success');
                    loadCompanies();
                },
                error: function(xhr) {
                    $btn.prop('disabled', false).html('Save Company');
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        for (let field in errors) {
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}_err`).text(errors[field][0]);
                        }
                    } else {
                        showToast('Error', xhr.responseJSON?.message || 'Something went wrong', 'error');
                    }
                }
            });
        });
    });

    function loadCompanies() {
        $('#companiesTableContainer').hide();
        $('.skeleton-wrapper').show();

        $.get('{{ route("companies.index") }}', function(html) {
            $('.skeleton-wrapper').hide();
            $('#companiesTableContainer').html(html).fadeIn();
            
            // Delete Handlers
            $('.delete-company-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                if (confirm('Are you sure you want to delete this company?')) {
                    $.ajax({
                        url: `/companies/${id}`,
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(res) {
                            showToast('Success', res.message, 'success');
                            loadCompanies();
                        },
                        error: function(xhr) {
                            showToast('Error', xhr.responseJSON?.message || 'Failed to delete company', 'error');
                        }
                    });
                }
            });

            // Edit Handlers
            $('.edit-company-btn').off('click').on('click', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                $.get(`/companies/${id}/edit`, function(company) {
                    openCompanyModal(company);
                });
            });
        });
    }

    function openCompanyModal(company = null) {
        $('#companyForm')[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#saveCompanyBtn').prop('disabled', false).html('Save Company');
        
        // Reset logo preview
        $('#logoPreviewContainer').hide();

        if (company) {
            $('#companyModalLabel').text('Edit Company');
            $('#companyId').val(company.id);
            $('#name').val(company.name);
            $('#email').val(company.email);
            $('#phone').val(company.phone);
            $('#website').val(company.website);
            $('#address').val(company.address);
            
            if (company.logo_path) {
                $('#logoPreview').attr('src', '/storage/' + company.logo_path);
                $('#logoPreviewContainer').show();
            }
        } else {
            $('#companyModalLabel').text('Create Company');
            $('#companyId').val('');
        }

        $('#companyModal').modal('show');
    }
</script>
@endpush
