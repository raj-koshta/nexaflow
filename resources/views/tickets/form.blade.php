<div class="offcanvas offcanvas-end" tabindex="-1" id="ticketOffcanvas" aria-labelledby="ticketOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 500px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important;">
        <h5 class="offcanvas-title fw-bold" id="ticketOffcanvasLabel">Create Ticket</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: var(--close-btn-filter);"></button>
    </div>
    <div class="offcanvas-body position-relative">
        <!-- Inner Loading Overlay for Save -->
        <div id="form-loading" class="position-absolute top-0 start-0 w-100 h-100 d-none justify-content-center align-items-center" style="background: rgba(0,0,0,0.5); z-index: 10; backdrop-filter: blur(2px);">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <form id="ticketForm">
            @csrf
            <input type="hidden" name="id" id="ticket_id">

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="subject" name="subject" required placeholder="Brief summary of the issue">
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Client (Optional)</label>
                <select class="form-select" id="client_id" name="client_id">
                    <option value="">No Client (Internal)</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Assignee</label>
                <select class="form-select" id="assigned_to" name="assigned_to">
                    <option value="">Unassigned</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="row mb-3">
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Status <span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="Open">Open</option>
                        <option value="Pending">Pending</option>
                        <option value="Resolved">Resolved</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                <div class="col-6">
                    <label class="form-label text-muted small text-uppercase">Priority <span class="text-danger">*</span></label>
                    <select class="form-select" id="priority" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                        <option value="Urgent">Urgent</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-muted small text-uppercase">Category <span class="text-danger">*</span></label>
                <select class="form-select" id="category" name="category" required>
                    <option value="Support">Support</option>
                    <option value="Bug">Bug Report</option>
                    <option value="Feature">Feature Request</option>
                    <option value="Billing">Billing</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted small text-uppercase">Description <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="5" required placeholder="Detailed explanation of the request or issue..."></textarea>
                <div class="invalid-feedback"></div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                <button type="submit" class="btn btn-primary px-4">Save Ticket</button>
            </div>
        </form>
    </div>
</div>
