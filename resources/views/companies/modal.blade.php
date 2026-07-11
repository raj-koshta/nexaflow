<!-- Company Modal -->
<div class="modal fade" id="companyModal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: var(--glass-border); border-radius: 12px;">
            <form id="companyForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="companyId" name="id">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="companyModalLabel">Create Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pb-2">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. NexaFlow LLC">
                            <div class="invalid-feedback" id="name_err"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="contact@company.com">
                            <div class="invalid-feedback" id="email_err"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Phone Number</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder="+1 (555) 123-4567">
                            <div class="invalid-feedback" id="phone_err"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small fw-medium">Website URL</label>
                            <input type="url" class="form-control" id="website" name="website" placeholder="https://company.com">
                            <div class="invalid-feedback" id="website_err"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small fw-medium">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2" placeholder="Full company address..."></textarea>
                            <div class="invalid-feedback" id="address_err"></div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label text-muted small fw-medium">Company Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <div class="invalid-feedback" id="logo_err"></div>
                            
                            <div id="logoPreviewContainer" class="mt-3" style="display:none;">
                                <p class="text-muted small mb-1">Current Logo:</p>
                                <img id="logoPreview" src="" class="rounded border" style="max-height: 60px;">
                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" value="1" id="remove_logo" name="remove_logo">
                                    <label class="form-check-label text-danger small" for="remove_logo">
                                        Remove Logo
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveCompanyBtn">Save Company</button>
                </div>
            </form>
        </div>
    </div>
</div>
