<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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

    /**
     * Export Clients Report to CSV
     */
    public function exportClients()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="clients_report_' . date('Y-m-d') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $columns = ['ID', 'Company Name', 'Contact Name', 'Email', 'Phone', 'Industry', 'Status', 'Created At'];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Company Name', 'Contact Name', 'Email', 'Phone', 'Industry', 'Status', 'Created At']);

            Client::chunk(500, function($clients) use ($file) {
                foreach ($clients as $client) {
                    $contactName = $client->first_name . ' ' . $client->last_name;
                    fputcsv($file, [
                        $client->id,
                        $client->company_name,
                        trim($contactName),
                        $client->email,
                        $client->phone,
                        $client->industry,
                        $client->status,
                        $client->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export Projects Report to CSV
     */
    public function exportProjects()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="projects_report_' . date('Y-m-d') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Project Name', 'Client Name', 'Status', 'Priority', 'Budget', 'Start Date', 'End Date', 'Created At']);

            Project::with('client')->chunk(500, function($projects) use ($file) {
                foreach ($projects as $project) {
                    fputcsv($file, [
                        $project->id,
                        $project->name,
                        $project->client->company_name ?? 'N/A',
                        $project->status,
                        $project->priority,
                        $project->budget,
                        $project->start_date ? $project->start_date->format('Y-m-d') : 'N/A',
                        $project->end_date ? $project->end_date->format('Y-m-d') : 'N/A',
                        $project->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    /**
     * Export Tasks Report to CSV
     */
    public function exportTasks()
    {
        $headers = [
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="tasks_report_' . date('Y-m-d') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0'
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Task Title', 'Project Name', 'Assignee', 'Status', 'Priority', 'Due Date', 'Created At']);

            Task::with(['project', 'assignee'])->chunk(500, function($tasks) use ($file) {
                foreach ($tasks as $task) {
                    fputcsv($file, [
                        $task->id,
                        $task->title,
                        $task->project->name ?? 'N/A',
                        $task->assignee->name ?? 'Unassigned',
                        $task->status,
                        $task->priority,
                        $task->due_date ? $task->due_date->format('Y-m-d') : 'N/A',
                        $task->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            });
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
