<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Tests\TestCase;

use App\Models\Adm;
use App\Models\Station;
use App\Models\Scooter;

class StationTest extends TestCase
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
    * GET /api/stations returns all stations for admin.
    */
    public function testStationsAdmin()
    {
        $response = $this->call(
            'GET',
            '/api/stations',
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
    * GET /api/stations/{id} returns single station's info for admin.
    */
    public function testSingleStationAdmin()
    {
        $stationId = 1;
        $response = $this->call(
            'GET',
            '/api/stations/' . $stationId,
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
    * GET /api/stations/{id}/scooters returns all of a station's scooters for admin.
    */
    public function testSingleStationScootersAdmin()
    {
        $stationId = 1;
        $response = $this->call(
            'GET',
            '/api/stations/' . $stationId . '/scooters',
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

    // /**
    //  * PUT /api/stations/{id} as admin updates station.
    //  */
    // public function testUpdateStationAdmin()
    // {
    //     $stationId = 1;
    //     $sendData = [
    //         'name' => 'Skövdelina',
    //         'radius' => 7,
    //         'lat_center' => 'setNull'
    //     ];
    //     $response = $this->call(
    //         'PUT',
    //         '/api/stations/' . $stationId,
    //         $sendData,
    //         ['admin_oauth_token' => $this->validAdmToken]
    //     );

    //     $response
    //         ->assertStatus(200);
        
    //     $updatedStation = Station::firstWhere('id', 1);

    //     $this->assertEquals($updatedStation->radius, $sendData['radius']);
    // }
}
