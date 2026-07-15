<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!Role::where('name', 'Administrator')->exists()) {
            Role::create(['name' => 'Administrator']);
        }
    }

    public function test_admin_can_view_projects_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');
        
        $client = Client::factory()->create(['created_by' => $admin->id]);

        Project::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('projects.index'));

        $response->assertStatus(200);
        $response->assertViewIs('projects.index');
    }

    public function test_admin_can_create_project()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');
        
        $client = Client::factory()->create(['created_by' => $admin->id]);

        $projectData = [
            'name' => 'New Website Redesign',
            'client_id' => $client->id,
            'status' => 'Planning',
            'priority' => 'High',
            'start_date' => now()->format('Y-m-d'),
            'deadline' => now()->addMonths(2)->format('Y-m-d'),
        ];

        $response = $this->actingAs($admin)->post(route('projects.store'), $projectData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'name' => 'New Website Redesign',
            'client_id' => $client->id,
            'status' => 'Planning'
        ]);
    }
}
