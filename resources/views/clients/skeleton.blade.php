<div id="clients-skeleton" class="d-none">
    <div class="card shadow-sm border-0" style="background: var(--card-bg); border: var(--glass-border);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-main);">
                    <thead style="background: rgba(255,255,255,0.02);">
                        <tr>
                            <th class="border-bottom-0"><div class="skeleton-text skeleton-heading"></div></th>
                            <th class="border-bottom-0"><div class="skeleton-text skeleton-heading"></div></th>
                            <th class="border-bottom-0"><div class="skeleton-text skeleton-heading"></div></th>
                            <th class="border-bottom-0"><div class="skeleton-text skeleton-heading"></div></th>
                            <th class="border-bottom-0 text-end"><div class="skeleton-text skeleton-heading ms-auto"></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 5; $i++)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="skeleton-avatar me-3"></div>
                                    <div>
                                        <div class="skeleton-text" style="width: 150px;"></div>
                                        <div class="skeleton-text mt-1" style="width: 100px; height: 12px;"></div>
                                    </div>
                                </div>
                            </td>
                            <td><div class="skeleton-text" style="width: 120px;"></div></td>
                            <td><div class="skeleton-text" style="width: 100px;"></div></td>
                            <td><div class="skeleton-text" style="width: 60px;"></div></td>
                            <td class="text-end"><div class="skeleton-text ms-auto" style="width: 80px;"></div></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
