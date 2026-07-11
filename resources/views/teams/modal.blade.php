<!-- Team Modal -->
<div class="modal fade" id="teamModal" tabindex="-1" aria-labelledby="teamModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: var(--glass-border); border-radius: 12px;">
            <form id="teamForm">
                @csrf
                <input type="hidden" id="teamId" name="id">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="teamModalLabel">Create Team</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pb-2">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Team Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg fs-6" id="name" name="name" required placeholder="e.g. Sales Department">
                        <div class="invalid-feedback" id="name_err"></div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Description</label>
                        <textarea class="form-control form-control-lg fs-6" id="description" name="description" rows="3" placeholder="Optional description of the team..."></textarea>
                        <div class="invalid-feedback" id="description_err"></div>
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveTeamBtn">Save Team</button>
                </div>
            </form>
        </div>
    </div>
</div>
