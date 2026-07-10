<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display the main Reports Dashboard.
     */
    public function index()
    {
        $stats = [
            'total_clients' => Client::count(),
            'total_projects' => Project::count(),
            'total_tasks' => Task::count(),
            'completed_tasks' => Task::where('status', 'Done')->count(),
        ];

        return view('reports.index', compact('stats'));
    }

    /**
     * Display the Clients Report.
     */
    public function clients()
    {
        $totalClients = Client::count();
        $clientsByIndustry = Client::select('industry', \DB::raw('count(*) as total'))
            ->whereNotNull('industry')
            ->groupBy('industry')
            ->orderByDesc('total')
            ->get();
            
        $clientsByStatus = Client::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $recentClients = Client::latest()->take(10)->get();

        return view('reports.clients', compact('totalClients', 'clientsByIndustry', 'clientsByStatus', 'recentClients'));
    }

    /**
     * Display the Projects Report.
     */
    public function projects()
    {
        $totalProjects = Project::count();
        $totalBudget = Project::sum('budget');
        
        $projectsByStatus = Project::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
            
        $projectsByPriority = Project::select('priority', \DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        $recentProjects = Project::with('client')->latest()->take(10)->get();

        return view('reports.projects', compact('totalProjects', 'totalBudget', 'projectsByStatus', 'projectsByPriority', 'recentProjects'));
    }

    /**
     * Display the Tasks Report.
     */
    public function tasks()
    {
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'Done')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $tasksByStatus = Task::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
            
        $overdueTasks = Task::where('status', '!=', 'Done')
            ->where('due_date', '<', Carbon::today())
            ->with(['project', 'assignee'])
            ->orderBy('due_date')
            ->take(10)
            ->get();

        return view('reports.tasks', compact('totalTasks', 'completedTasks', 'completionRate', 'tasksByStatus', 'overdueTasks'));
    }
}
