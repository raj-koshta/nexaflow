<?php

namespace Tests\Feature;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class LeadTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        if (!Role::where('name', 'Administrator')->exists()) {
            Role::create(['name' => 'Administrator']);
        }
    }

    public function test_admin_can_view_leads()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        Lead::factory(2)->create([
            'assigned_to' => $admin->id,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('leads.index'));

        $response->assertStatus(200);
        $response->assertViewIs('leads.index');
    }

    public function test_admin_can_convert_lead_to_client()
    {
        $this->withoutExceptionHandling();
        $admin = User::factory()->create();
        $admin->assignRole('Administrator');

        $lead = Lead::factory()->create([
            'status' => 'new',
            'assigned_to' => $admin->id,
            'created_by' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->post(route('leads.convert', $lead->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Lead converted to Client successfully!',
        ]);

        // Check DB transactions worked
        $this->assertDatabaseHas('clients', [
            'email' => $lead->email
        ]);
        
        $this->assertDatabaseHas('contacts', [
            'email' => $lead->email
        ]);
        
        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'status' => 'qualified'
        ]);
    }
}
