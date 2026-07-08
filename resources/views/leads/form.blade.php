<!-- Offcanvas Sidebar for Lead Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="leadOffcanvas" aria-labelledby="leadOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <h5 class="offcanvas-title fw-bold" id="leadOffcanvasLabel">Add New Lead</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(var(--bs-theme) === 'dark' ? 1 : 0);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="leadForm">
            <input type="hidden" id="lead_id" name="id">
            
            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Contact Information</h6>
            
            <div class="mb-3">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required>
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

            <div class="mb-4">
                <label for="company" class="form-label">Company Name</label>
                <input type="text" class="form-control" id="company" name="company">
                <div class="invalid-feedback"></div>
            </div>

            <h6 class="text-uppercase text-muted fw-bold mb-3" style="font-size: 0.75rem; letter-spacing: 1px;">Lead Details</h6>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="new">New</option>
                        <option value="contacted">Contacted</option>
                        <option value="qualified">Qualified</option>
                        <option value="lost">Lost</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="source" class="form-label">Source</label>
                    <select class="form-select" id="source" name="source">
                        <option value="">Select Source...</option>
                        <option value="website">Website</option>
                        <option value="referral">Referral</option>
                        <option value="social_media">Social Media</option>
                        <option value="cold_call">Cold Call</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="expected_value" class="form-label">Expected Value ($)</label>
                    <input type="number" step="0.01" class="form-control" id="expected_value" name="expected_value" placeholder="0.00">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="mb-4">
                <label for="remarks" class="form-label">Remarks / Notes</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="3"></textarea>
                <div class="invalid-feedback"></div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer p-3 border-top d-flex justify-content-end" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <button type="button" class="btn btn-link text-muted text-decoration-none me-3" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="button" class="btn btn-primary px-4" id="saveLeadBtn">
            <span class="indicator-label">Save Lead</span>
            <span class="indicator-progress d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...
            </span>
        </button>
    </div>
</div>
