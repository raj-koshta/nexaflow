<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AiActivityLog;
use Carbon\Carbon;

class AiAnalyticsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $stats = [
            'total_requests' => AiActivityLog::count(),
            'today_requests' => AiActivityLog::whereDate('created_at', $today)->count(),
            'avg_response_time' => round(AiActivityLog::where('is_successful', true)->avg('processing_time') ?? 0),
            'failed_requests' => AiActivityLog::where('is_successful', false)->count(),
        ];

        // Ensure we don't divide by zero
        $successRate = $stats['total_requests'] > 0 
            ? round((($stats['total_requests'] - $stats['failed_requests']) / $stats['total_requests']) * 100, 1) 
            : 100;
        
        $stats['success_rate'] = $successRate;

        $logs = AiActivityLog::with('user')->latest()->paginate(20);

        return view('ai.dashboard.index', compact('stats', 'logs'));
    }
}
