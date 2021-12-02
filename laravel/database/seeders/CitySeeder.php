<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\City;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        City::create([
            'name' => 'Skövde',
            'lat_center' => 58.3941248,
            'lon_center' => 13.8534907,
            'radius' => 5
        ]);

        City::create([
            'name' => 'Lund',
            'lat_center' => 55.7106955,
            'lon_center' => 13.2013123,
            'radius' => 5
        ]);

        City::create([
            'name' => 'Uppsala',
            'lat_center' => 59.8615337,
            'lon_center' => 17.6543391,
            'radius' => 5
        ]);
    }
}
