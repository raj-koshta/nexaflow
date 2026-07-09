<div id="followups-skeleton" class="d-none">
    <div class="row g-4">
        @for ($i = 0; $i < 6; $i++)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex gap-2">
                            <div class="skeleton-avatar rounded px-3 py-1" style="width: 80px; height: 24px;"></div>
                            <div class="skeleton-avatar rounded px-3 py-1" style="width: 100px; height: 24px;"></div>
                        </div>
                        <div class="skeleton-avatar rounded-circle" style="width: 24px; height: 24px;"></div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="skeleton-text mb-2" style="width: 90%;"></div>
                        <div class="skeleton-text" style="width: 60%;"></div>
                    </div>
                    
                    <div class="d-flex align-items-center mb-3">
                        <div class="skeleton-avatar rounded-circle me-2" style="width: 32px; height: 32px;"></div>
                        <div class="skeleton-text" style="width: 120px;"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top p-3 d-flex justify-content-between align-items-center" style="border-color: var(--border-color) !important;">
                    <div class="skeleton-text" style="width: 100px; height: 20px;"></div>
                    <div class="skeleton-avatar rounded" style="width: 130px; height: 36px;"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
