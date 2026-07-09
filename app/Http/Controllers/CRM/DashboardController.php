<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Services\CRM\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the CRM Dashboard.
     */
    public function index()
    {
        $metrics = $this->dashboardService->getMetrics();
        return view('dashboard.index', compact('metrics'));
    }
}
