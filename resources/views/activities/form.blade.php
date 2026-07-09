<!-- Offcanvas Sidebar for Activity Form -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="activityOffcanvas" aria-labelledby="activityOffcanvasLabel" style="background: var(--primary-bg); border-left: var(--glass-border); width: 450px; max-width: 100%;">
    <div class="offcanvas-header border-bottom" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <h5 class="offcanvas-title fw-bold" id="activityOffcanvasLabel">Log Activity</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close" style="filter: invert(var(--bs-theme) === 'dark' ? 1 : 0);"></button>
    </div>
    <div class="offcanvas-body">
        <form id="activityForm">
            <input type="hidden" id="activity_id" name="id">
            
            <div class="mb-4">
                <label class="form-label d-block fw-medium">Link this Activity to <span class="text-danger">*</span></label>
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
                <div class="col-md-6">
                    <label for="type" class="form-label">Activity Type <span class="text-danger">*</span></label>
                    <select class="form-select" id="type" name="type" required>
                        <option value="">Select type...</option>
                        @foreach($types as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label for="activity_date" class="form-label">Date & Time <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control" id="activity_date" name="activity_date" required>
                    <div class="invalid-feedback"></div>
                </div>
            </div>

            <div class="mb-3">
                <label for="title" class="form-label">Title / Subject <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title" placeholder="e.g. Initial intro call" required>
                <div class="invalid-feedback"></div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Notes & Details</label>
                <textarea class="form-control" id="description" name="description" rows="5" placeholder="Summarize the interaction..."></textarea>
                <div class="invalid-feedback"></div>
            </div>
        </form>
    </div>
    <div class="offcanvas-footer p-3 border-top d-flex justify-content-end" style="border-color: var(--border-color) !important; background: var(--secondary-bg);">
        <button type="button" class="btn btn-link text-muted text-decoration-none me-3" data-bs-dismiss="offcanvas">Cancel</button>
        <button type="button" class="btn btn-primary px-4" id="saveActivityBtn">
            <span class="indicator-label">Save Activity</span>
            <span class="indicator-progress d-none">
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...
            </span>
        </button>
    </div>
</div>
