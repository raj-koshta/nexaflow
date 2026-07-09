<!-- Offcanvas Sidebar for Follow Up Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="followUpOffcanvas" aria-labelledby="followUpOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <h5 class="offcanvas-title fw-bold" id="followUpOffcanvasLabel">Schedule Follow Up</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(var(--bs-theme) === 'dark' ? 1 : 0);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="followUpForm">
            <input type="hidden" id="followup_id" name="id">
            
            <div class="mb-4">
                <label class="form-label d-block fw-medium">Link this Follow Up to <span class="text-danger">*</span></label>
                <div class="btn-group w-100 mb-2" role="group">
                    <input type="radio" class="btn-check" name="entity_type" id="link_client" value="client" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="link_client">Client</label>
                  
                    <input type="radio" class="btn-check" name="entity_type" id="link_lead" value="lead" autocomplete="off">
                    <label class="btn btn-outline-primary" for="link_lead">Lead</label>
                </div>
                
                <div id="client_select_wrapper">
                    <select class="form-select" id="client_id" name="client_id">
                        <option value="">Select a Client...</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->company_name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>

                <div id="lead_select_wrapper" class="d-none">
                    <select class="form-select" id="lead_id" name="lead_id">
                        <option value="">Select a Lead...</option>
                        @foreach($leads as $lead)
                            <option value="{{ $lead->id }}">{{ $lead->name }} ({{ $lead->company }})</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-7">
                    <label for="follow_date" class="form-label">Date <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" id="follow_date" name="follow_date" required>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-5">
                    <label for="follow_time" class="form-label">Time (Opt)</label>
                    <input type="time" class="form-control" id="follow_time" name="follow_time">
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="mb-3">
                <label for="assigned_to" class="form-label">Assign To</label>
                <select class="form-select" id="assigned_to" name="assigned_to">
                    <option value="">Assign to me (default)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="remarks" class="form-label">Remarks / Notes</label>
                <textarea class="form-control" id="remarks" name="remarks" rows="5" placeholder="What needs to be done?"></textarea>
                <div class="invalid-feedback"></div>
            </div>
            
            <div id="status_wrapper" class="mb-3 d-none">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer p-3 border-top d-flex justify-content-end" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <button type="button" class="btn btn-link text-muted text-decoration-none me-3" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="button" class="btn btn-primary px-4" id="saveFollowUpBtn">
            <span class="indicator-label">Save Schedule</span>
            <span class="indicator-progress d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...
            </span>
        </button>
    </div>
</div>
