<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadConversionTest extends TestCase
{
    use RefreshDatabase;

    public function test_lead_created_with_status_won_automatically_converts_to_customer(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

        $lead = Lead::create([
            'name' => 'John Tech',
            'email' => 'john@techcorp.com',
            'phone' => '+1234567890',
            'company' => 'Tech Corp',
            'source' => Lead::SOURCE_WEB,
            'status' => Lead::STATUS_WON,
            'assigned_to' => $admin->id,
        ]);

        $lead->refresh();

        $this->assertNotNull($lead->customer_id);
        $this->assertDatabaseHas('customers', [
            'email' => 'john@techcorp.com',
            'name' => 'John Tech',
            'company' => 'Tech Corp',
        ]);
    }

    public function test_updating_lead_status_to_won_converts_lead_to_customer(): void
    {
        $lead = Lead::create([
            'name' => 'Sarah Lead',
            'email' => 'sarah@innovate.com',
            'phone' => '+9876543210',
            'company' => 'Innovate Ltd',
            'source' => Lead::SOURCE_ADS,
            'status' => Lead::STATUS_IN_PROGRESS,
        ]);

        $this->assertNull($lead->customer_id);
        $this->assertDatabaseMissing('customers', ['email' => 'sarah@innovate.com']);

        $lead->update(['status' => Lead::STATUS_WON]);
        $lead->refresh();

        $this->assertNotNull($lead->customer_id);
        $this->assertDatabaseHas('customers', [
            'email' => 'sarah@innovate.com',
            'id' => $lead->customer_id,
        ]);
    }

    public function test_converting_lead_with_existing_customer_email_links_without_duplication(): void
    {
        $customer = Customer::create([
            'name' => 'Existing Customer',
            'email' => 'repeat@client.com',
            'phone' => '111-222-3333',
            'company' => 'Client Inc',
        ]);

        $lead = Lead::create([
            'name' => 'Repeat Opportunity',
            'email' => 'repeat@client.com',
            'source' => Lead::SOURCE_REFERRAL,
            'status' => Lead::STATUS_WON,
        ]);

        $lead->refresh();

        $this->assertEquals($customer->id, $lead->customer_id);
        $this->assertEquals(1, Customer::where('email', 'repeat@client.com')->count());
    }

    public function test_only_admin_can_delete_lead(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $salesUser = User::factory()->create(['role' => User::ROLE_SALES_USER]);

        $lead = Lead::create([
            'name' => 'Test Lead Delete',
            'email' => 'delete@test.com',
            'source' => Lead::SOURCE_WEB,
            'status' => Lead::STATUS_NEW,
        ]);

        // Sales user attempt to delete -> forbidden 403
        $response = $this->actingAs($salesUser)->delete(route('leads.destroy', $lead));
        $response->assertStatus(403);
        $this->assertDatabaseHas('leads', ['id' => $lead->id]);

        // Admin attempt to delete -> success
        $response = $this->actingAs($admin)->delete(route('leads.destroy', $lead));
        $response->assertRedirect(route('leads.index'));
        $this->assertDatabaseMissing('leads', ['id' => $lead->id]);
    }
}
