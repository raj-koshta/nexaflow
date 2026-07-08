<!-- Offcanvas Sidebar for Client Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="clientOffcanvas" aria-labelledby="clientOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <h5 class="offcanvas-title fw-bold" id="clientOffcanvasLabel">Add New Client</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(var(--bs-theme) === 'dark' ? 1 : 0);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="clientForm">
            <input type="hidden" id="client_id" name="id">
            
            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Basic Information</h6>
            
            <div class="mb-3">
                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="company_name" name="company_name" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="url" class="form-control" id="website" name="website" placeholder="https://">
                <div class="invalid-feedback"></div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="industry" class="form-label">Industry</label>
                    <input type="text" class="form-control" id="industry" name="industry">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Billing & Location</h6>

            <div class="mb-3">
                <label for="gst_number" class="form-label">GST / Tax Number</label>
                <input type="text" class="form-control" id="gst_number" name="gst_number">
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="address" class="form-label">Street Address</label>
                <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="city" class="form-label">City</label>
                    <input type="text" class="form-control" id="city" name="city">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="state" class="form-label">State/Province</label>
                    <input type="text" class="form-control" id="state" name="state">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="postal_code" class="form-label">Postal Code</label>
                    <input type="text" class="form-control" id="postal_code" name="postal_code">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="country" class="form-label">Country</label>
                    <input type="text" class="form-control" id="country" name="country">
                    <div class="invalid-feedback"></div>
                </div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer p-3 border-top d-flex justify-content-end" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <button type="button" class="btn btn-link text-muted text-decoration-none me-3" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="button" class="btn btn-primary px-4" id="saveClientBtn">
            <span class="indicator-label">Save Client</span>
            <span class="indicator-progress d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...
            </span>
        </button>
    </div>
</div>
