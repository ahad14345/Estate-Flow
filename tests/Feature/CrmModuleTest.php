<?php

use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('seeds realistic crm sample data for the dashboard and management screens', function () {
    $this->seed(DatabaseSeeder::class);

    expect(Customer::count())->toBeGreaterThanOrEqual(30)
        ->and(Lead::count())->toBeGreaterThanOrEqual(20)
        ->and(FollowUp::count())->toBeGreaterThanOrEqual(15);
});

it('loads the crm dashboard and allows creating a customer', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/crm/dashboard');
    $response->assertOk();
    $response->assertSee('CRM Dashboard');

    $response = $this->post('/crm/customers', [
        'full_name' => 'Amina Rahman',
        'email' => 'amina@example.com',
        'phone_number' => '01710000001',
        'address' => 'House 12, Gulshan',
        'city' => 'Dhaka',
        'customer_type' => 'Buyer',
        'preferred_property_type' => 'Apartment',
        'budget' => '5000000',
        'assigned_employee' => 'Salma',
        'status' => 'Lead',
        'notes' => 'Interested in duplexes',
    ]);

    $response->assertRedirect('/crm/customers');
    $this->assertDatabaseHas('customers', ['email' => 'amina@example.com']);
});

it('renders the crm layout without a partial-loading overlay', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get('/crm/dashboard');

    $response->assertOk();
    $response->assertDontSee('Loading...');
    $response->assertSee('EstateFlow ERP');
});
