<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!Role::where('name', 'Administrator')->exists()) {
            Role::create(['name' => 'Administrator']);
        }
    }

    public function test_admin_can_view_tickets_list()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');
        
        $client = Client::factory()->create(['created_by' => $admin->id]);

        Ticket::factory(3)->create([
            'client_id' => $client->id,
            'created_by' => $admin->id,
            'assigned_to' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('tickets.index'));

        $response->assertStatus(200);
        $response->assertViewIs('tickets.index');
    }

    public function test_admin_can_create_ticket()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');
        
        $client = Client::factory()->create(['created_by' => $admin->id]);

        $ticketData = [
            'subject' => 'Server is down',
            'client_id' => $client->id,
            'status' => 'Open',
            'priority' => 'High',
            'category' => 'technical',
            'description' => 'Please help the server is down.'
        ];

        $response = $this->actingAs($admin)->post(route('tickets.store'), $ticketData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('tickets', [
            'subject' => 'Server is down',
            'client_id' => $client->id,
            'status' => 'Open',
            'priority' => 'High'
        ]);
    }
}
