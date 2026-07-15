<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!Role::where('name', 'Administrator')->exists()) {
            Role::create(['name' => 'Administrator']);
        }
    }

    public function test_admin_can_view_tasks_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $client = Client::factory()->create(['created_by' => $admin->id]);
        $project = Project::factory()->create(['client_id' => $client->id, 'created_by' => $admin->id]);
        
        Task::factory(3)->create([
            'project_id' => $project->id,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tasks.index');
    }

    public function test_admin_can_create_task()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $client = Client::factory()->create(['created_by' => $admin->id]);
        $project = Project::factory()->create(['client_id' => $client->id, 'created_by' => $admin->id]);
        
        $taskData = [
            'title' => 'Design Homepage',
            'project_id' => $project->id,
            'status' => 'Todo',
            'priority' => 'High',
        ];

        $response = $this->actingAs($admin)->post(route('tasks.store'), $taskData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Design Homepage',
            'project_id' => $project->id,
            'status' => 'Todo'
        ]);
    }
}
