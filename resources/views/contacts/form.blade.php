<!-- Offcanvas Sidebar for Contact Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="contactOffcanvas" aria-labelledby="contactOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <h5 class="offcanvas-title fw-bold" id="contactOffcanvasLabel">Add New Contact</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(var(--bs-theme) === 'dark' ? 1 : 0);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="contactForm">
            <input type="hidden" id="contact_id" name="id">
            
            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Association</h6>
            
            <div class="mb-4">
                <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value="">Select a Client...</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Personal Details</h6>
            
            <div class="mb-3">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="designation" class="form-label">Designation</label>
                    <input type="text" class="form-control" id="designation" name="designation" placeholder="e.g. CEO">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="department" class="form-label">Department</label>
                    <input type="text" class="form-control" id="department" name="department" placeholder="e.g. Sales">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <h6 class="text-uppercase text-muted fw-bold mb-3 mt-4" style="font-size: 0.75rem; letter-spacing: 1px;">Contact Information</h6>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
                <div class="invalid-feedback"></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="form-check form-switch mt-4 mb-4">
                <input class="form-check-input" type="checkbox" role="switch" id="is_primary" name="is_primary" value="1">
                <label class="form-check-label" for="is_primary">Set as Primary Contact for this Client</label>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer p-3 border-top d-flex justify-content-end" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <button type="button" class="btn btn-link text-muted text-decoration-none me-3" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="button" class="btn btn-primary px-4" id="saveContactBtn">
            <span class="indicator-label">Save Contact</span>
            <span class="indicator-progress d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...
            </span>
        </button>
    </div>
</div>
