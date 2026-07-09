<div id="activities-skeleton" class="d-none">
    <div class="position-relative py-3 ps-3 ps-md-4">
        <!-- Timeline vertical line skeleton -->
        <div class="position-absolute top-0 bottom-0 border-start" style="left: 1.5rem; border-color: var(--border-color) !important; border-width: 2px !important;"></div>
        
        @for ($i = 0; $i < 4; $i++)
        <div class="position-relative mb-4 pb-2">
            <!-- Timeline Icon skeleton -->
            <div class="position-absolute skeleton-avatar rounded-circle d-flex align-items-center justify-content-center" 
                 style="left: -1rem; width: 36px; height: 36px; top: 0; border: 3px solid var(--primary-bg); z-index: 2;">
            </div>
            
            <div class="ms-4 ps-3">
                <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="skeleton-text" style="width: 200px;"></div>
                            <div class="skeleton-text" style="width: 100px;"></div>
                        </div>
                        <div class="skeleton-text mb-1" style="width: 100%;"></div>
                        <div class="skeleton-text mb-3" style="width: 80%;"></div>
                        
                        <div class="d-flex gap-2">
                            <div class="skeleton-text" style="width: 80px; height: 24px; border-radius: 12px;"></div>
                            <div class="skeleton-text" style="width: 120px; height: 24px; border-radius: 12px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>
