<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

use App\Models\Adm;

class LoggTest extends TestCase
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

        $this->validAdmUsername = 'adm-username';
        $this->validAdmToken = 'adm-token';
        // create valid admin
        $validAdm = new Adm;
        $validAdm->username = $this->validAdmUsername;
        $validAdm->token = $this->validAdmToken;
        $validAdm->save();
    }

    /**
    * GET /api/logs returns all trip logs for admin.
    */
    public function testLoggsAdmin()
    {
        $response = $this->call(
            'GET',
            '/api/logs',
            [],
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(2)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 1)
                            ->where('username', 'customer1')
                            ->etc()
                    )
        );
    }
}
