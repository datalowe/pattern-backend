<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

use App\Models\Adm;
use App\Models\City;
use App\Models\Scooter;

class CityTest extends TestCase
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
    * GET /api/cities returns all cities for admin.
    */
    public function testCitiesAdmin()
    {
        $response = $this->call(
            'GET',
            '/api/cities',
            [],
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(3)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 1)
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/cities/{id} returns single city's info for admin.
    */
    public function testSingleCityAdmin()
    {
        $cityId = 1;
        $response = $this->call(
            'GET',
            '/api/cities/' . $cityId,
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
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/cities/{id}/scooters returns all of a city's scooters for admin.
    */
    public function testSingleCityScootersAdmin()
    {
        $cityId = 1;
        $response = $this->call(
            'GET',
            '/api/cities/' . $cityId . '/scooters',
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
                            ->where('customer_id', null)
                            ->etc()
                    )
        );
    }

    /**
    * GET /api/cities/{id}/stations returns all of a city's stations for admin.
    */
    public function testSingleCityStationsAdmin()
    {
        $cityId = 1;
        $response = $this->call(
            'GET',
            '/api/cities/' . $cityId . '/stations',
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
                            ->where('location', 'Arena Skövde')
                            ->etc()
                    )
        );
    }

    /**
     * PUT /api/cities/{id} as admin updates city.
     */
    public function testUpdateCityAdmin()
    {
        $cityId = 1;
        $sendData = [
            'name' => 'Skövdelina',
            'radius' => 7,
            'lat_center' => 'setNull'
        ];
        $response = $this->call(
            'PUT',
            '/api/cities/' . $cityId,
            $sendData,
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);
        
        $updatedCity = City::firstWhere('id', 1);

        $this->assertEquals($updatedCity->radius, $sendData['radius']);
    }
}
