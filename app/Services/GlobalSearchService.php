<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;

class GlobalSearchService
{
    /**
     * Search across multiple models and return standardized results.
     */
    public function search(string $query, int $limit = 5): array
    {
        $results = [];

        // Search Clients
        $clients = Client::where('company_name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('client_code', 'like', "%{$query}%")
            ->limit($limit)
            ->get();
            
        foreach ($clients as $client) {
            $results[] = [
                'type' => 'Client',
                'title' => $client->company_name,
                'subtitle' => $client->email ?? $client->client_code,
                'icon' => 'bi-buildings-fill',
                'color' => 'primary',
                'url' => route('clients.show', $client->id),
            ];
        }

        // Search Leads
        $leads = Lead::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('company', 'like', "%{$query}%")
            ->limit($limit)
            ->get();

        foreach ($leads as $lead) {
            $results[] = [
                'type' => 'Lead',
                'title' => $lead->name,
                'subtitle' => $lead->company ?? $lead->email,
                'icon' => 'bi-funnel-fill',
                'color' => 'warning',
                'url' => route('leads.show', $lead->id),
            ];
        }

        // Search Projects
        $projects = Project::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->limit($limit)
            ->get();

        foreach ($projects as $project) {
            $results[] = [
                'type' => 'Project',
                'title' => $project->name,
                'subtitle' => 'Status: ' . ucfirst($project->status),
                'icon' => 'bi-briefcase-fill',
                'color' => 'success',
                'url' => route('projects.show', $project->id),
            ];
        }

        // Search Tasks
        $tasks = Task::where('title', 'like', "%{$query}%")
            ->limit($limit)
            ->get();

        foreach ($tasks as $task) {
            $results[] = [
                'type' => 'Task',
                'title' => $task->title,
                'subtitle' => 'Project: ' . ($task->project ? $task->project->name : 'N/A'),
                'icon' => 'bi-check-square-fill',
                'color' => 'info',
                'url' => route('tasks.index', ['task_id' => $task->id]), // Ensure route exists, or use edit/modal
            ];
        }
        
        // Search Tickets
        $tickets = Ticket::where('subject', 'like', "%{$query}%")
            ->orWhere('ticket_number', 'like', "%{$query}%")
            ->limit($limit)
            ->get();

        foreach ($tickets as $ticket) {
            $results[] = [
                'type' => 'Ticket',
                'title' => '#' . $ticket->ticket_number . ' - ' . $ticket->subject,
                'subtitle' => 'Status: ' . ucfirst($ticket->status),
                'icon' => 'bi-ticket-detailed',
                'color' => 'danger',
                'url' => route('tickets.show', $ticket->id),
            ];
        }

        // Group the results by Type
        $grouped = [];
        foreach ($results as $result) {
            $grouped[$result['type']][] = $result;
        }

        return $grouped;
    }
}
