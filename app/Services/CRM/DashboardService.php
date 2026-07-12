<?php

namespace App\Services\CRM;

use App\Models\Client;
use App\Models\Lead;
use App\Models\FollowUp;
use App\Models\Activity;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
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

        // Projects
        $totalProjects = Project::count();
        $activeProjects = Project::where('status', 'In Progress')->count();
        $completedProjects = Project::where('status', 'Completed')->count();

        // Tasks
        $pendingTasks = Task::where('status', '!=', 'Completed')->count();
        $completedTasks = Task::where('status', 'Completed')->count();
        
        $myTasks = Task::with('project')
                        ->where('assigned_to', Auth::id())
                        ->where('status', '!=', 'Completed')
                        ->orderBy('due_date', 'asc')
                        ->take(5)
                        ->get();

        // Tickets
        $openTickets = Ticket::where('status', 'Open')->count();
        $resolvedTickets = Ticket::where('status', 'Resolved')->count();
        
        $criticalTicketsList = Ticket::with('client')
                                    ->where('status', 'Open')
                                    ->orderBy('created_at', 'desc')
                                    ->take(5)
                                    ->get();

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

        // Latest Activities
        $latestActivities = Activity::with(['client', 'lead', 'creator'])
                                    ->orderBy('activity_date', 'desc')
                                    ->take(8)
                                    ->get();

        // Chart Data: Last 6 Months Clients vs Leads
        $months = [];
        $clientsChart = [];
        $leadsChart = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            $clientsChart[] = Client::whereMonth('created_at', $month->month)
                                    ->whereYear('created_at', $month->year)
                                    ->count();
            $leadsChart[] = Lead::whereMonth('created_at', $month->month)
                                ->whereYear('created_at', $month->year)
                                ->count();
        }

        // Chart Data: Project Status
        $projectStatusChart = [
            'labels' => ['Not Started', 'In Progress', 'On Hold', 'Completed'],
            'data' => [
                Project::where('status', 'Not Started')->count(),
                Project::where('status', 'In Progress')->count(),
                Project::where('status', 'On Hold')->count(),
                Project::where('status', 'Completed')->count(),
            ]
        ];

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
            'projects' => [
                'total' => $totalProjects,
                'active' => $activeProjects,
                'completed' => $completedProjects,
            ],
            'tasks' => [
                'pending' => $pendingTasks,
                'completed' => $completedTasks,
                'my_tasks' => $myTasks,
            ],
            'tickets' => [
                'open' => $openTickets,
                'resolved' => $resolvedTickets,
                'critical_list' => $criticalTicketsList,
            ],
            'follow_ups' => [
                'today_count' => $todaysFollowUpsCount,
                'today_list' => $todaysFollowUps,
            ],
            'activities' => [
                'latest' => $latestActivities,
            ],
            'charts' => [
                'growth' => [
                    'labels' => $months,
                    'clients' => $clientsChart,
                    'leads' => $leadsChart,
                ],
                'projects' => $projectStatusChart,
            ]
        ];
    }
}
