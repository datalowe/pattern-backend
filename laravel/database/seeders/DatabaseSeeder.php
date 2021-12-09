<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CitySeeder::class,
            AdmSeeder::class,
            CustomerSeeder::class,
            StationSeeder::class,
            ScooterSeeder::class,
            LoggSeeder::class
        ]);
    }
}
