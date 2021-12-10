<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;

use Illuminate\Contracts\Cache\LockTimeoutException;

use Tests\TestCase;

use Mockery\MockInterface;

use App\Models\Adm;
use App\Models\Customer;
use App\Models\Scooter;

use App\Http\Controllers\Sctr\ScooterController;

class ScooterTest extends TestCase
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
        // create valid customer (implicitly setting funds to 0
        // and payment_terms to 'invoice')
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
        
        // there should only be 2 scooters, since the scooter seeder
        // creates 4, but one of them has status 'maintenance' and another
        // has battery level 5% (below the 10% limit for when scooters are to
        // be shown to customers)
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(2)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 2)
                            ->where('city_id', 2)
                            ->etc()
                    )
        );
    }

    /**
     * GET /api/scooters/{id} as customer returns single scooter.
     */
    public function testSingleScooterCustomer()
    {
        $response = $this->call(
            'GET',
            '/api/scooters/1',
            [],
            ['oauth_token' => $this->validCustomerToken]
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
    * GET /api/scooter-client/scooters as scooter simulator returns all scooters.
    */
    public function testScootersSimulator()
    {
        $response = $this->call(
            'GET',
            '/api/scooter-client/scooters',
            [],
            []
        );

        $response
            ->assertStatus(200);
        
        // there should 4 scooters, since the scooter seeder
        // creates 4, and simulator should get access to all of them.
        $response
            ->assertJson(fn (AssertableJson $json) => 
                $json
                    ->has(4)
                    ->first(fn ($json) =>
                        $json
                            ->where('id', 1)
                            ->where('city_id', 1)
                            ->etc()
                    )
        );
    }

    /**
     * GET /api/scooter-client/scooters/{id} as scooter simulator returns single scooter.
     */
    public function testSingleScooterSimulator()
    {
        $response = $this->call(
            'GET',
            '/api/scooter-client/scooters/1',
            [],
            []
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
     * PUT /api/scooters/{id} updates scooter.
     */
    public function testUpdateScooterAdmin()
    {
        $updateScootedId = 1;
        $sendData = [
            'customer_id' => 'setNull',
            'lat_pos' => 50,
            'lon_pos' => 13,
            'speed_kph' => 5,
            'battery_level' => 50,
            'status' => 'active'
        ];

        $response = $this->call(
            'PUT',
            '/api/scooters/' . $updateScootedId,
            $sendData,
            ['admin_oauth_token' => $this->validAdmToken]
        );

        $response
            ->assertStatus(200);

        $updatedScooter = Scooter::firstWhere('id', 1);

        $this->assertEquals($updatedScooter->battery_level, 50);
    }

    /**
     * POST /api/scooter-client/scooters/flush-cache flushes cache to database 
     */
    public function testSyncCacheWithDatabase()
    {
        $putData = [
            'id' => 1,
            'customer_id' => 1,
            'lat_pos' => 50,
            'lon_pos' => 13,
            'speed_kph' => 5,
            'battery_level' => 50,
            'status' => 'active'
        ];
        Cache::put('scooterNoStationCache', [$putData], 60000);

        $response = $this->call(
            'POST',
            '/api/scooter-client/scooters/flush-cache',
        );

        $response
            ->assertStatus(200);

        $updatedScooter = Scooter::firstWhere('id', 1);

        $this->assertEquals($updatedScooter->battery_level, 50);
    }

    /**
     * PUT /api/scooter-client/scooters/{id} updates cache and triggers
     * cache to database flush when no previous
     * request has been done (ie throttling isn't ongoing).
     */
    public function testUpdateScooterCache()
    {
        $updateScootedId = 1;
        $sendData = [
            'customer_id' => 1,
            'lat_pos' => 50,
            'lon_pos' => 13,
            'speed_kph' => 5,
            'battery_level' => 50,
            'status' => 'active'
        ];

        $response = $this->call(
            'PUT',
            '/api/scooter-client/scooters/' . $updateScootedId,
            $sendData
        );

        $response
            ->assertStatus(200);

        $updatedScooter = Scooter::firstWhere('id', 1);

        $this->assertEquals($updatedScooter->battery_level, 50);
    }

    /**
     * PUT /api/scooter-client/scooters/{id} tries to update cache but
     * skips doing so if the cache is being blocked by another request process/thread.
     */
    public function testUpdateScooterCacheBlocked()
    {
        $updateScootedId = 1;
        $sendData = [
            'customer_id' => 1,
            'status' => 'active'
        ];

        $lockMock = $this->mock(Service::class, function (MockInterface $mock) {
            $mock->shouldReceive('block')->once()->andThrow(new LockTimeoutException);
            $mock->shouldReceive('release')->once();
        });

        Cache::shouldReceive('lock')
            ->once()
            ->with('scooterNoStationCache-lock', 5)
            ->andReturn($lockMock);

        // return a very high/'late' last cache send time to ensure that no
        // attempt at flushing to database is done
        Cache::shouldReceive('get')
            ->once()
            ->with('lastCacheSendTime', 0)
            ->andReturn(999999999999);

        $response = $this->call(
            'PUT',
            '/api/scooter-client/scooters/' . $updateScootedId,
            $sendData
        );

        $response
            ->assertStatus(200);

        $nonUpdatedScooter = Scooter::firstWhere('id', 1);

        $this->assertNotEquals($nonUpdatedScooter->customer_id, 1);
    }

    /**
     * PUT /api/scooter-client/scooters/{id} 3 times causes
     * data to be saved in cache until POST to flush endpoint
     * is done.
     */
    public function testUpdateScooterCacheMultiple()
    {   
        $updateScootedIds = [1, 2, 3];
        $sendDataArrs = [
            [
                'customer_id' => 1,
                'lat_pos' => 50,
                'lon_pos' => 13,
                'speed_kph' => 5,
                'battery_level' => 50,
                'status' => 'active'
            ],
            [
                'customer_id' => 2,
                'lat_pos' => 50,
                'lon_pos' => 13,
                'speed_kph' => 5,
                'battery_level' => 50,
                'status' => 'maintenance'
            ],
            [
                'customer_id' => 3,
                'station_id' => "setNull",
                'lat_pos' => 50,
                'lon_pos' => 13,
                'speed_kph' => 5,
                'battery_level' => 50,
                'status' => 'active'
            ]
        ];

        for ($x = 0; $x < 3; $x++) {
            $response = $this->call(
                'PUT',
                '/api/scooter-client/scooters/' . $updateScootedIds[$x],
                $sendDataArrs[$x]
            );
            $response
                ->assertStatus(200);
        } 

        $updatedScooter1 = Scooter::firstWhere('id', 1);
        $nonUpdatedScooter = Scooter::firstWhere('id', 2);

        $this->assertEquals($updatedScooter1->battery_level, 50);
        $this->assertNotEquals($nonUpdatedScooter->battery_level, 50);

        $preFlushStationCache = Cache::get('scooterStationCache', []);
        $preFlushNoStationCache = Cache::get('scooterNoStationCache', []);

        $this->assertEquals($preFlushStationCache[0]['id'], 3);
        $this->assertEquals($preFlushNoStationCache[0]['id'], 2);

        $response = $this->call(
            'POST',
            '/api/scooter-client/scooters/flush-cache',
        );

        $response
            ->assertStatus(200);
        
        $updatedScooter2 = Scooter::firstWhere('id', 2);
        $this->assertEquals($updatedScooter2->battery_level, 50);
    }

    /**
     * calling pullCacheWithLock when cache is being blocked by other thread/process
     * results in empty array being returned and database not being synced
     */
    public function testPullCacheWithLockBlocked()
    {
        $lockMock = $this->mock(Service::class, function (MockInterface $mock) {
            $mock->shouldReceive('block')->andThrow(new LockTimeoutException);
            $mock->shouldReceive('release');
        });

        Cache::shouldReceive('lock')
            ->andReturn($lockMock);
        
        Cache::shouldReceive('put');
        
        ScooterController::syncCacheWithDatabase();
    }
}
