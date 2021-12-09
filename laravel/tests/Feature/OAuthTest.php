<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Laravel\Socialite\Facades\Socialite;
use Mockery\MockInterface;

use Tests\TestCase;

use App\Models\Adm;
use App\Models\Customer;

class OAuthTest extends TestCase
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

        $this->validCustomerUsername = 'customer-username';
        $this->validCustomerToken = 'customer-token';
        Customer::create([
            'username' => $this->validCustomerUsername,
            'token' => $this->validCustomerToken
        ]);

        $this->validAdmUsername = 'adm-username';
        $this->validAdmToken = 'adm-token';
        // create valid admin
        $validAdm = new Adm;
        $validAdm->username = $this->validAdmUsername;
        $validAdm->token = $this->validAdmToken;
        $validAdm->save();
    }

    /**
    * GET /api/auth/github/redirect returns login URL for non-logged-in client.
    */
    public function testGithubCustomerRedirect()
    {
        $response = $this->call(
            'GET',
            '/api/auth/github/redirect'
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has('login_url')
        );
    }

    /**
    * GET /api/auth/github/callback creates new customer if it is not already known.
    */
    public function testGithubCustomerCallback()
    {
        $userMock = $this->mock(Service::class, function (MockInterface $mock) {
            $mock->shouldReceive('getNickName')->andReturn('mockuser');
            $mock->token = 'mocktoken';
            $mock->expiresIn = 60;
        });
        $statelessMock = $this->mock(Service::class, function (MockInterface $mock) use (&$userMock) {
            $mock->shouldReceive('user')->andReturn($userMock);
        });
        $driverMock = $this->mock(Service::class, function (MockInterface $mock) use (&$statelessMock) {
            $mock->shouldReceive('stateless')->andReturn($statelessMock);
        });
        Socialite::shouldReceive('driver')->andReturn($driverMock);

        $response = $this->call(
            'GET',
            '/api/auth/github/callback'
        );

        $response
            ->assertStatus(200);

        $response
            ->assertSee('Hej');

        $numMockUsers = Customer::where('username', 'mockuser')->count();
        $this->assertEquals($numMockUsers, 1);
    }

    /**
    * GET /api/auth/github/redirect/admin returns login URL for non-logged-in client.
    */
    public function testGithubAdminRedirect()
    {
        $response = $this->call(
            'GET',
            '/api/auth/github/redirect/admin'
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has('login_url')
        );
    }

    /**
    * GET /api/auth/github/callback/admin returns message requesting to close admin interface
    * if username is not known.
    */
    public function testGithubAdminCallback()
    {
        $userMock = $this->mock(Service::class, function (MockInterface $mock) {
            $mock->shouldReceive('getNickName')->andReturn('mockuser');
            $mock->token = 'mocktoken';
            $mock->expiresIn = 60;
        });
        $statelessMock = $this->mock(Service::class, function (MockInterface $mock) use (&$userMock) {
            $mock->shouldReceive('user')->andReturn($userMock);
        });
        $driverMock = $this->mock(Service::class, function (MockInterface $mock) use (&$statelessMock) {
            $mock->shouldReceive('stateless')->andReturn($statelessMock);
        });
        Socialite::shouldReceive('driver')->andReturn($driverMock);

        $response = $this->call(
            'GET',
            '/api/auth/github/callback/admin'
        );

        $response
            ->assertStatus(200);

        $response
            ->assertSee('Du verkar inte vara en administratör.');
    }

    /**
    * GET /api/auth/github/callback/admin returns message reporting succesful login
    * if username is in admin table.
    */
    public function testGithubAdminCallbackActualAdmin()
    {
        $userMock = $this->mock(Service::class, function (MockInterface $mock) {
            $mock->shouldReceive('getNickName')->andReturn('admin1');
            $mock->token = 'mocktoken';
            $mock->expiresIn = 60;
        });
        $statelessMock = $this->mock(Service::class, function (MockInterface $mock) use (&$userMock) {
            $mock->shouldReceive('user')->andReturn($userMock);
        });
        $driverMock = $this->mock(Service::class, function (MockInterface $mock) use (&$statelessMock) {
            $mock->shouldReceive('stateless')->andReturn($statelessMock);
        });
        Socialite::shouldReceive('driver')->andReturn($driverMock);

        $response = $this->call(
            'GET',
            '/api/auth/github/callback/admin'
        );

        $response
            ->assertStatus(200);

        $response
            ->assertSee('Du är nu inloggad som admin');
    }

    /**
    * GET /api/auth/github/check-usertype as admin returns user type admin.
    */
    public function testCheckUserTypeAdmin()
    {
        $response = $this->call(
            'GET',
            '/api/auth/github/check-usertype',
            [],
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);
        
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->where('user_type', 'admin')
                    ->etc()
        );
    }

    /**
    * GET /api/auth/github/check-usertype as customer returns user type customer.
    */
    public function testCheckUserTypeCustomer()
    {

        $response = $this->call(
            'GET',
            '/api/auth/github/check-usertype',
            [],
            ['oauth_token' => $this->validCustomerToken]
        );

        $response
            ->assertStatus(200);
        
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->where('user_type', 'customer')
                    ->etc()
        );
    }

    /**
    * GET /api/auth/github/check-usertype as customer returns user type customer.
    */
    public function testCheckUserTypeNotLoggedIn()
    {

        $response = $this->call(
            'GET',
            '/api/auth/github/check-usertype',
            [],
            []
        );

        $response
            ->assertStatus(200);
        
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->where('user_type', 'not_logged_in')
                    ->etc()
        );
    }
}
