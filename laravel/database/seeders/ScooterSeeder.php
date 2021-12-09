<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Scooter;

class ScooterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Scooter::create([
            'city_id' => 1,
            'station_id' => 1,
            'lat_pos' => 58.3788262,
            'lon_pos' => 13.8179737
        ]);

        Scooter::create([
            'city_id' => 2,
            'station_id' => null,
            'lat_pos' => 55.68794267,
            'lon_pos' => 13.25154203,
        ]);

        Scooter::create([
            'city_id' => 2,
            'station_id' => null,
            'lat_pos' => 55.70387715,
            'lon_pos' => 13.19489434,
            'status' => 'maintenance'
        ]);

        Scooter::create([
            'city_id' => 3,
            'station_id' => null,
            'lat_pos' => 59.8212918,
            'lon_pos' => 17.6953749
        ]);
    }
}
