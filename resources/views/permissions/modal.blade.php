<!-- Permission Modal -->
<div class="modal fade" id="permissionModal" tabindex="-1" aria-labelledby="permissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: var(--glass-border); border-radius: 12px;">
            <form id="permissionForm">
                @csrf
                <input type="hidden" id="permissionId" name="id">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="permissionModalLabel">Create Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pb-2">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Permission Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg fs-6" id="name" name="name" required placeholder="e.g. edit articles">
                        <div class="form-text text-muted small">Use consistent naming like 'create tasks', 'delete users', etc.</div>
                        <div class="invalid-feedback" id="name_err"></div>
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="savePermissionBtn">Save Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>
