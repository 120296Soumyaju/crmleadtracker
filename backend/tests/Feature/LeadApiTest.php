<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Lead;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LeadApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_api_request_returns_401(): void
    {
        $response = $this->getJson('/api/leads');
        $response->assertStatus(401);
    }

    public function test_api_login_returns_token_and_user_details(): void
    {
        $user = User::factory()->create([
            'email' => 'apiuser@test.com',
            'password' => 'password123',
            'role' => User::ROLE_SALES_USER,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'apiuser@test.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user' => ['id', 'name', 'email', 'role'],
                'token',
            ]);
    }

    public function test_authenticated_user_can_fetch_leads_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Lead::create([
            'name' => 'API Lead 1',
            'email' => 'api1@test.com',
            'source' => Lead::SOURCE_WEB,
            'status' => Lead::STATUS_NEW,
        ]);

        $response = $this->getJson('/api/leads');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'source', 'status', 'is_converted'],
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total'],
            ]);
    }

    public function test_leads_api_search_filtering_and_pagination(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Lead::create(['name' => 'Alpha Acme', 'email' => 'alpha@acme.com', 'company' => 'Acme Corp', 'source' => Lead::SOURCE_WEB, 'status' => Lead::STATUS_NEW]);
        Lead::create(['name' => 'Beta Beta', 'email' => 'beta@beta.com', 'company' => 'Beta Inc', 'source' => Lead::SOURCE_ADS, 'status' => Lead::STATUS_IN_PROGRESS]);

        // Search query test
        $searchResponse = $this->getJson('/api/leads?search=Acme');
        $searchResponse->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Alpha Acme');

        // Filter status test
        $statusResponse = $this->getJson('/api/leads?status=In Progress');
        $statusResponse->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Beta Beta');

        // Pagination metadata test
        $pageResponse = $this->getJson('/api/leads?per_page=1');
        $pageResponse->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonPath('meta.total', 2);
    }

    public function test_creating_lead_via_api_with_won_status_auto_converts_customer(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'name' => 'Won API Lead',
            'email' => 'wonapi@client.com',
            'phone' => '+15559998888',
            'company' => 'API Corp',
            'source' => Lead::SOURCE_ADS,
            'status' => Lead::STATUS_WON,
        ];

        $response = $this->postJson('/api/leads', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.status', 'Won')
            ->assertJsonPath('data.is_converted', true);

        $this->assertDatabaseHas('customers', [
            'email' => 'wonapi@client.com',
            'name' => 'Won API Lead',
        ]);
    }

    public function test_authenticated_user_can_fetch_customers_via_api(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Customer::create([
            'name' => 'API Customer',
            'email' => 'apicustomer@test.com',
            'company' => 'API Customer Ltd',
        ]);

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'company'],
                ],
                'meta',
            ]);
    }

    public function test_customers_api_search_and_pagination(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Customer::create(['name' => 'Starlight Media', 'email' => 'info@starlight.com', 'company' => 'Starlight Corp']);
        Customer::create(['name' => 'Vortex Soft', 'email' => 'contact@vortex.dev', 'company' => 'Vortex Ltd']);

        // Search customer test
        $searchResponse = $this->getJson('/api/customers?search=Starlight');
        $searchResponse->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Starlight Media');

        // Pagination test
        $pageResponse = $this->getJson('/api/customers?per_page=1');
        $pageResponse->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('meta.per_page', 1)
            ->assertJsonPath('meta.total', 2);
    }
}
