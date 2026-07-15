<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Make sure roles exist
        if (!Role::where('name', 'Administrator')->exists()) {
            Role::create(['name' => 'Administrator']);
        }
    }

    public function test_unauthenticated_users_cannot_access_clients()
    {
        $response = $this->get(route('clients.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_admin_can_view_clients_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        Client::factory(3)->create([
            'created_by' => $admin->id
        ]);

        $response = $this->actingAs($admin)->get(route('clients.index'));

        $response->assertStatus(200);
        $response->assertViewIs('clients.index');
    }

    public function test_authenticated_admin_can_create_client()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $clientData = [
            'company_name' => $this->faker->company,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => '1234567890',
            'status' => 'active'
        ];

        $response = $this->actingAs($admin)->post(route('clients.store'), $clientData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'email' => $clientData['email']
        ]);
    }
}
