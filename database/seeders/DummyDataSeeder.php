<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Task;
use App\Models\Milestone;
use App\Models\Lead;
use App\Models\FollowUp;
use App\Models\Note;
use App\Models\Document;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\AiPromptTemplate;
use App\Models\User;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Dummy Data Seeding...');

        // 1. Create Companies
        $this->command->info('Creating Companies...');
        $companies = Company::factory(10)->create();

        // 2. Create Clients with Contacts
        $this->command->info('Creating Clients & Contacts...');
        
        $clients = Client::factory(20)->create();
        foreach ($clients as $client) {
            Contact::factory(2)->create([
                'client_id' => $client->id,
                'is_primary' => false,
            ]);
            // Set one primary contact
            Contact::factory()->create([
                'client_id' => $client->id,
                'is_primary' => true,
            ]);
        }

        // 3. Create Projects, Tasks, and Milestones
        $this->command->info('Creating Projects, Tasks, and Milestones...');
        $admin = User::first() ?? User::factory()->create(); // fallback to factory if no users exist
        
        foreach ($clients->random(15) as $client) {
            $project = Project::factory()->create([
                'client_id' => $client->id,
                'created_by' => $admin->id,
            ]);

            Task::factory(5)->create([
                'project_id' => $project->id,
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            Milestone::factory(2)->create([
                'project_id' => $project->id,
                'created_by' => $admin->id,
            ]);
            // Documents are for clients or leads, not projects
        }

        // 4. Create Leads with FollowUps and Notes
        $this->command->info('Creating Leads, FollowUps, and Notes...');
        $leads = Lead::factory(25)->create([
            'assigned_to' => $admin->id,
            'created_by' => $admin->id,
        ]);

        foreach ($leads as $lead) {
            FollowUp::factory(rand(1, 3))->create([
                'lead_id' => $lead->id,
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            Note::factory(rand(1, 4))->create([
                'lead_id' => $lead->id,
                'created_by' => $admin->id
            ]);
            
            Document::factory(rand(0, 2))->create([
                'lead_id' => $lead->id,
                'created_by' => $admin->id
            ]);
        }

        // 5. Create Support Tickets and Replies
        $this->command->info('Creating Tickets & Replies...');
        foreach ($clients->random(10) as $client) {
            $ticket = Ticket::factory()->create([
                'client_id' => $client->id,
                'assigned_to' => $admin->id,
                'created_by' => $admin->id,
            ]);

            TicketReply::factory(rand(2, 5))->create([
                'ticket_id' => $ticket->id,
                'user_id' => $admin->id,
            ]);
        }

        // 6. Create AI Prompt Templates
        $this->command->info('Creating AI Prompt Templates...');
        AiPromptTemplate::factory(5)->create();

        $this->command->info('Dummy Data Seeding Completed Successfully! 🎉');
    }
}
