<?php

namespace App\Services\CRM;

use App\Models\Client;
use App\Models\Lead;
use App\Models\FollowUp;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    /**
     * Get all dashboard metrics.
     */
    public function getMetrics(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::today();
        
        // Clients
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'Active')->count();
        $inactiveClients = Client::where('status', 'Inactive')->count();
        $newClients = Client::where('created_at', '>=', $startOfMonth)->count();

        // Leads
        $totalLeads = Lead::count();
        $convertedLeads = Lead::where('status', 'Won')->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 1) : 0;

        // Follow Ups
        $todaysFollowUpsCount = FollowUp::where('status', 'Pending')
                                        ->whereDate('follow_date', $today)
                                        ->count();
                                        
        $todaysFollowUps = FollowUp::with(['client', 'lead'])
                                    ->where('status', 'Pending')
                                    ->whereDate('follow_date', $today)
                                    ->orderBy('follow_time', 'asc')
                                    ->take(5)
                                    ->get();

        // Upcoming Meetings
        $upcomingMeetingsCount = Activity::where('type', 'Meeting')
                                         ->where('activity_date', '>=', Carbon::now())
                                         ->count();
                                         
        $upcomingMeetings = Activity::with(['client', 'lead'])
                                    ->where('type', 'Meeting')
                                    ->where('activity_date', '>=', Carbon::now())
                                    ->orderBy('activity_date', 'asc')
                                    ->take(5)
                                    ->get();

        // Latest Activities
        $latestActivities = Activity::with(['client', 'lead', 'creator'])
                                    ->orderBy('activity_date', 'desc')
                                    ->take(10)
                                    ->get();

        return [
            'clients' => [
                'total' => $totalClients,
                'active' => $activeClients,
                'inactive' => $inactiveClients,
                'new_this_month' => $newClients,
            ],
            'leads' => [
                'total' => $totalLeads,
                'converted' => $convertedLeads,
                'conversion_rate' => $conversionRate,
            ],
            'follow_ups' => [
                'today_count' => $todaysFollowUpsCount,
                'today_list' => $todaysFollowUps,
            ],
            'meetings' => [
                'upcoming_count' => $upcomingMeetingsCount,
                'upcoming_list' => $upcomingMeetings,
            ],
            'activities' => [
                'latest' => $latestActivities,
            ],
        ];
    }
}
