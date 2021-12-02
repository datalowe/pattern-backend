<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

use App\Models\Customer;

class ScooterTest extends TestCase
{
    // Tell laravel to migrate the database before and after each test.
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        // use database/seeeders to fill the database with testing data
        $this->artisan('db:seed');

        $this->validCustomerUsername = 'customer-username';
        $this->validCustomerToken = 'customer-token';
        // create valid customer (implicitly setting funds to 0
        // and payment_terms to 'invoice')
        Customer::create([
            'username' => $this->validCustomerUsername,
            'token' => $this->validCustomerToken
        ]);
    }

    /**
     * Request to /api/scooters returns filtered scooters for customer.
     */
    public function testScootersCustomer()
    {
        $response = $this->call(
            'GET',
            '/api/scooters',
            [],
            ['oauth_token' => $this->validCustomerToken]
        );

        $response
            ->assertStatus(200);
        
        // there should only be 3 scooters, since the scooter seeder
        // creates 4, but one of them has status 'maintenance'
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(3)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 1)
                            ->where('city_id', 1)
                            ->etc()
                    )
        );
    }
}
