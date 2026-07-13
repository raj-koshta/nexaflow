<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Client;
use App\Models\Lead;
use App\Models\Project;
use App\Models\Task;
use App\Models\Ticket;
use App\Models\Contact;
use Illuminate\Support\Facades\Hash;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create a Default Admin User if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@nexaflow.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        // Assign the administrator role if Spatie permissions are set up
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrator']);
            $admin->assignRole($role);
        }

        // 2. Create some other users (Team Members)
        $users = User::factory(5)->create();
        $users->push($admin);

        // 3. Create Clients
        $clients = Client::factory(15)->create();

        // 4. Create Leads
        $leads = Lead::factory(20)->create();

        // 5. Create Contacts for Clients
        foreach ($clients as $client) {
            Contact::factory(rand(1, 3))->create([
                'client_id' => $client->id,
            ]);
        }

        // 6. Create Projects
        foreach ($clients as $client) {
            $projects = Project::factory(rand(1, 4))->create([
                'client_id' => $client->id,
            ]);

            // 7. Create Tasks for Projects
            foreach ($projects as $project) {
                Task::factory(rand(5, 15))->create([
                    'project_id' => $project->id,
                    'assigned_to' => $users->random()->id,
                ]);
            }
        }

        // 8. Create Support Tickets
        foreach ($clients as $client) {
            Ticket::factory(rand(0, 3))->create([
                'client_id' => $client->id,
                'assigned_to' => $users->random()->id,
            ]);
        }
    }
}
