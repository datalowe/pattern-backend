<?php

namespace App\Http\Controllers\Sctr;

use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request; // for POST route
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\Scooter; // model of table
use App\Models\Customer;

// Customer class

class ScooterController extends Controller
{
    // how long to wait (in seconds) inbetween synchronizations
    // between scooter update cache and database scooter table
    private static $cacheSendLatency = 5;

    public function getAllScooters(Request $req)
    {
        // NOTE NEW! Filtering based on who requested the data (customers
        // only get active scooters) TODO ensure that correct filtering
        // is applied for customers (more criteria needed? might be appropriate
        // to create a custom method on Scooter class if filtering becomes complex)
        if (Customer::isCustomerReq($req)) {
            return Scooter::where('status', 'active')->get();
        }
        return Scooter::all();
    }

    // $id from web.php contains scooter_id, $body contains key-value from POST
    public function updateScooter($idNr, Request $body)
    {
        // find scooter by its primary key
        $scooter = Scooter::find($idNr);
        // get all columns from request body
        $columns = $body->all();
        // iterate through all columns, replace value if column was found
        foreach ($columns as $column => $value) {
            // if value is "setNull", and column value is not already null, set it to null, otherwise nothing
            $value == "setNull" ? (
                $body->$column != null ? $scooter->$column = null : null
                // if not "setNull" is passed but another value, set column to that value
            ) : $scooter->$column = $value;
        }

        // update scooter
        $scooter->save();
    }

    public function updateScooterCache($idNr, Request $body)
    {
        // get all columns from request body
        $columns = $body->all();
        $columns['id'] = $idNr;
        // this branching is necessary because of how DB::table()->upsert() works (which is
        // used in syncCacheWithDatabase). when it updates/inserts multiple entries,
        // they must all have _values for the same keys/columns_. the scooter client
        // sometimes sends specifications for station_id, sometimes not, and so
        // these types of requests must be handled differently. otherwise the
        // DB upsert call will cause a database/SQL error.
        if (array_key_exists('station_id', $columns)) {
            $cacheColName = 'scooterStationCache';
        } else {
            $cacheColName = 'scooterNoStationCache';
        }
        foreach ($columns as $column => $value) {
            if ($value == "setNull") {
                $columns[$column] = null;
            }
        }

        // try to access cache key/value, wait for max. 5s if it is currently being blocked
        $lock = Cache::lock($cacheColName, 5);

        try {
            // lock the cache key/value to avoid race conditions (other requests coming
            // in and changing the key's value before updates here have finished)
            $lock->block(5);
            $scooterCache = Cache::get($cacheColName, []);
            $scooterCache = array_merge($scooterCache, [$columns]);
            Cache::put($cacheColName, $scooterCache, 60000);
        } catch (LockTimeoutException $e) {
            $output = new \Symfony\Component\Console\Output\ConsoleOutput();
            $output->writeln("failed to get lock");
        } finally {
            optional($lock)->release();
        }
        // call function which checks if it's time to send cached updates to
        // database, and in that case sends them off
        $this->syncCacheWithDatabaseCheck();
    }

    private function syncCacheWithDatabaseCheck()
    {
        $currentTime = time();
        $lastCacheSendTime = Cache::get('lastCacheSendTime', 0);
        $timeSinceSync = $currentTime - $lastCacheSendTime;
        if ($timeSinceSync >= self::$cacheSendLatency) {
            $this->syncCacheWithDatabase();
        }
    }

    public function syncCacheWithDatabase()
    {
        foreach (['scooterStationCache', 'scooterNoStationCache'] as $colName) {
            $cachedData = Cache::pull($colName, []);
            if (count($cachedData) == 0) {
                continue;
            }
            DB::table('scooter')->upsert(
                $cachedData,
                ['id'],
                array_keys($cachedData[0])
            );
        }
        $currentTime = time();
        Cache::put('lastCacheSendTime', $currentTime, 60000);
    }
}
