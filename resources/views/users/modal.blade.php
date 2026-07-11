<!-- User Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: var(--glass-border); border-radius: 12px;">
            <form id="userForm">
                @csrf
                <input type="hidden" id="userId" name="id">
                
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold" id="userModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body pb-2">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Full Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg fs-6" id="name" name="name" required placeholder="John Doe">
                        <div class="invalid-feedback" id="name_err"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Email Address <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-lg fs-6" id="email" name="email" required placeholder="john@example.com">
                        <div class="invalid-feedback" id="email_err"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Roles</label>
                        <div class="row g-2 mt-1">
                            @forelse($roles as $role)
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role_{{ $role->id }}">
                                        <label class="form-check-label text-main" for="role_{{ $role->id }}">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-muted small fst-italic">No roles available</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-medium">Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg fs-6" id="password" name="password" placeholder="Min 8 characters">
                        <div class="form-text" id="passwordSectionHelp"></div>
                        <div class="invalid-feedback" id="password_err"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-medium">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control form-control-lg fs-6" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password">
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="saveUserBtn">Save User</button>
                </div>
            </form>
        </div>
    </div>
</div>
