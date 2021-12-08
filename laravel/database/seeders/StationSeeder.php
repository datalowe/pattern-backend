<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\City;
use App\Models\Station;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Station::create([
            'city_id' => 1,
            'location' => 'Arena Skövde',
            'lat_center' => 58.393154,
            'lon_center' => 13.8380477,
            'radius' => 0.002,
            'type' => 'charge'
        ]);

        Station::create([
            'city_id' => 1,
            'location' => 'Sjukhuset',
            'lat_center' => 58.4072554,
            'lon_center' => 13.8248402,
            'radius' => 0.002,
            'type' => 'park'
        ]);

        Station::create([
            'city_id' => 2,
            'location' => 'Victoriastadion',
            'lat_center' => 55.7048954,
            'lon_center' => 13.1770515,
            'radius' => 0.002,
            'type' => 'charge'
        ]);
    }
}
