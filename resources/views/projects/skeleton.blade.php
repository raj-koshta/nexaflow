<div class="card shadow-sm border-0 mb-4 skeleton-card" style="background: var(--card-bg); border: var(--glass-border);">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th class="border-bottom-0 py-3" style="width: 25%"><div class="skeleton-box rounded" style="width: 60%; height: 16px;"></div></th>
                        <th class="border-bottom-0 py-3" style="width: 20%"><div class="skeleton-box rounded" style="width: 50%; height: 16px;"></div></th>
                        <th class="border-bottom-0 py-3" style="width: 15%"><div class="skeleton-box rounded" style="width: 40%; height: 16px;"></div></th>
                        <th class="border-bottom-0 py-3" style="width: 20%"><div class="skeleton-box rounded" style="width: 70%; height: 16px;"></div></th>
                        <th class="border-bottom-0 py-3" style="width: 10%"><div class="skeleton-box rounded" style="width: 50%; height: 16px;"></div></th>
                        <th class="border-bottom-0 py-3 text-end" style="width: 10%"><div class="skeleton-box rounded ms-auto" style="width: 30%; height: 16px;"></div></th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 5; $i++)
                    <tr>
                        <td class="py-3">
                            <div class="d-flex align-items-center">
                                <div class="skeleton-box rounded me-3" style="width: 40px; height: 40px;"></div>
                                <div class="flex-grow-1">
                                    <div class="skeleton-box rounded mb-2" style="width: 80%; height: 14px;"></div>
                                    <div class="skeleton-box rounded" style="width: 40%; height: 12px;"></div>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <div class="skeleton-box rounded mb-2" style="width: 70%; height: 14px;"></div>
                            <div class="skeleton-box rounded" style="width: 50%; height: 12px;"></div>
                        </td>
                        <td class="py-3"><div class="skeleton-box rounded-pill" style="width: 60px; height: 24px;"></div></td>
                        <td class="py-3">
                            <div class="skeleton-box rounded mb-2" style="width: 100%; height: 8px;"></div>
                            <div class="skeleton-box rounded" style="width: 30%; height: 12px;"></div>
                        </td>
                        <td class="py-3"><div class="skeleton-box rounded-pill" style="width: 80px; height: 24px;"></div></td>
                        <td class="py-3 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <div class="skeleton-box rounded" style="width: 28px; height: 28px;"></div>
                                <div class="skeleton-box rounded" style="width: 28px; height: 28px;"></div>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
