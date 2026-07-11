<!-- Role Modal -->
<div class="modal fade" id="roleModal" tabindex="-1" aria-labelledby="roleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border: var(--glass-border); border-radius: 12px;">
            <form id="roleForm">
                @csrf
                <input type="hidden" id="roleId" name="id">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="roleModalLabel">Create Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pb-2">
                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Role Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg fs-6" id="name" name="name" required placeholder="e.g. Sales Manager">
                        <div class="invalid-feedback" id="name_err"></div>
                    </div>
                    
                    <h6 class="fw-bold mb-3 border-bottom pb-2">Assign Permissions</h6>
                    <div class="row g-3">
                        @forelse($permissions as $permission)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="permission_{{ $permission->id }}">
                                    <label class="form-check-label text-main" for="permission_{{ $permission->id }}">
                                        {{ $permission->name }}
                                    </label>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning d-flex align-items-center">
                                    <i class="bi bi-exclamation-triangle me-2"></i> No permissions available in the system. Create permissions first.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveRoleBtn">Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>
