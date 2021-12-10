<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

use App\Models\Adm;
use App\Models\Associate;
use App\Models\Customer;

class CustomerTest extends TestCase
{
    // Tell laravel to migrate the database before and after each test.
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        // use database/seeeders to fill the database with testing data
        $this->artisan('db:seed');
        // clear cache
        $this->artisan('cache:clear');

        $this->validCustomerUsername4 = 'customer-username-4';
        $this->validCustomerToken4 = 'customer-token-4';
        // create valid customer (implicitly setting funds to 0
        // and payment_terms to 'invoice')
        Customer::create([
            'username' => $this->validCustomerUsername4,
            'token' => $this->validCustomerToken4
        ]);
        $this->validCustomerUsername5 = 'customer-username-5';
        $this->validCustomerToken5 = 'customer-token-5';
        Customer::create([
            'username' => $this->validCustomerUsername5,
            'token' => $this->validCustomerToken5
        ]);

        $this->validAdmUsername = 'adm-username';
        $this->validAdmToken = 'adm-token';
        // create valid admin
        $validAdm = new Adm;
        $validAdm->username = $this->validAdmUsername;
        $validAdm->token = $this->validAdmToken;
        $validAdm->save();

        $this->validAssociateName = 'associate-name';
        $this->validApiKey = 'associate-apikey';
        $validAssociate = new Associate;
        $validAssociate->client = $this->validAssociateName;
        $validAssociate->apikey = $this->validApiKey;
        $validAssociate->save();
    }

    /**
    * GET /api/users returns all users for admin.
    */
    public function testCustomersAdmin()
    {
        $response = $this->call(
            'GET',
            '/api/users',
            [],
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(5)
                    ->has(3, fn ($json) =>
                        $json
                            ->where('id', 4)
                            ->where('username', $this->validCustomerUsername4)
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/users returns 401 error response for non-authenticated client.
    */
    public function testCustomersNonAuth()
    {
        $response = $this->call(
            'GET',
            '/api/users',
            [],
            []
        );

        $response
            ->assertStatus(401);
    }

    /**
    * GET /api/users returns 401 error response for regular customer.
    */
    public function testCustomersRegularCustomer()
    {
        $response = $this->call(
            'GET',
            '/api/users',
            [],
            ['oauth_token' => $this->validCustomerToken4]
        );

        $response
            ->assertStatus(401);
    }

    /**
    * GET /api/users as associate returns all users.
    */
    public function testCustomersAssociate()
    {
        $response = $this
            ->withHeaders(['api-key' => $this->validApiKey])
            ->get('/api/users');

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(5)
                    ->has(3, fn ($json) =>
                        $json
                            ->where('id', 4)
                            ->where('username', $this->validCustomerUsername4)
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/users returns 401 error response for client with invalid associate api key.
    */
    public function testCustomersFalseAssociate()
    {
        $response = $this
            ->withHeaders(['api-key' => 'invalid-api-key'])
            ->get('/api/users');

        $response
            ->assertStatus(401);
    }

    /**
    * GET /api/users/{id} returns single customer's info for admin.
    */
    public function testSingleCustomerAdmin()
    {
        $customerId = 5;
        $response = $this->call(
            'GET',
            '/api/users/' . $customerId,
            [],
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(1)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 5)
                            ->where('username', $this->validCustomerUsername5)
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/users/{id} returns single customer's info for that very customer.
    */
    public function testSingleCustomerOwner()
    {
        $customerId = 4;
        $response = $this->call(
            'GET',
            '/api/users/' . $customerId,
            [],
            ['oauth_token' => $this->validCustomerToken4]
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(1)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 4)
                            ->where('username', $this->validCustomerUsername4)
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/users/{id} as customer returns 403 error response if the request {id} parameter does not match 
    * the customer's own id.
    */
    public function testSingleCustomerNonowner()
    {
        $customerId = 5;
        $response = $this->call(
            'GET',
            '/api/users/' . $customerId,
            [],
            ['oauth_token' => $this->validCustomerToken4]
        );

        $response
            ->assertStatus(403);
    }

    /**
    * GET /api/users/{id}/logs returns single customer's logs for admin.
    */
    public function testSingleCustomersLogsAdmin()
    {
        $customerId = 1;
        $response = $this->call(
            'GET',
            '/api/users/' . $customerId . '/logs',
            [],
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(1)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 1)
                            ->where('start_cost', '20.00')
                            ->where('username', 'customer1')
                            ->etc()
                    )
        );
    }

    /**
    * PUT /api/users/{id} as admin updates customer info
    */
    public function testSingleCustomerUpdateAdmin()
    {
        $customerId = 1;
        $sendData = [
            "token" => "setNull",
            "funds" => 9001,
            "payment_terms" => 'prepaid'
        ];
        $response = $this->call(
            'PUT',
            '/api/users/' . $customerId,
            $sendData,
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $updatedCustomer = Customer::find($customerId);

        $this->assertEquals($updatedCustomer->payment_terms, 'prepaid');
    }
}
