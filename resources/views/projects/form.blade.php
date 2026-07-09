<div class="offcanvas offcanvas-end" tabindex="-1" id="projectOffcanvas" aria-labelledby="projectOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 500px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important;">
        <h5 class="offcanvas-title fw-bold" id="projectOffcanvasLabel">Create Project</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: var(--close-btn-filter);"></button>
    </div>
    <div class="offcanvas-body position-relative">
        <!-- Inner Loading Overlay for Save -->
        <div id="form-loading" class="position-absolute top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); z-index: 10; backdrop-filter: blur(2px);">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <form id="projectForm">
            @csrf
            <input type="hidden" name="id" id="project_id">

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Project Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" required placeholder="e.g. Website Redesign">
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Associated Client <span class="text-danger">*</span></label>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value="">Select a Client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Planning">Planning</option>
                        <option value="Active">Active</option>
                        <option value="On Hold">On Hold</option>
                        <option value="Completed">Completed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Priority <span class="text-danger">*</span></label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                        <option value="Critical">Critical</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date">
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Due Date</label>
                    <input type="date" class="form-control" id="due_date" name="due_date">
                </div>
            </div>

            <div class="mb-3" id="progress-container" style="display: none;">
                <label class="form-label text-muted small text-uppercase">Progress (%)</label>
                <input type="number" class="form-control" id="progress" name="progress" min="0" max="100">
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Budget ($)</label>
                <input type="number" step="0.01" min="0" class="form-control" id="budget" name="budget" placeholder="0.00">
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small text-uppercase">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Brief project goals..."></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Project</button>
            </div>
        </form>
    </div>
</div>
