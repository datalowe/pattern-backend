<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Logg;

class LoggSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        Logg::insert([
            [
                'customer_id' => '1',
                'scooter_id' => '1',
                'start_time' => $faker->dateTimeBetween('-3 hours', '-2 hours'),
                'end_time' => $faker->dateTimeBetween('-2 hours', '-1 hours'),
                'start_lat' => 58.3788562,
                'start_lon' => 13.8179337,
                'end_lat' => 58.3788262,
                'end_lon' => 13.8179737,
                'start_cost' => 20,
                'travel_cost' => 3.33,
                'parking_cost' => 10,
                'total_cost' => 20 + 3.33 + 10
            ],
        ]);

        Logg::insert([
            [
                'customer_id' => '2',
                'scooter_id' => '2',
                'start_time' => $faker->dateTimeBetween('-3 hours', '-2 hours'),
                'end_time' => $faker->dateTimeBetween('-2 hours', '-1 hours'),
                'start_lat' => 55.68793267,
                'start_lon' => 13.25156203,
                'end_lat' => 55.68794267,
                'end_lon' => 13.25154203,
                'start_cost' => 20,
                'travel_cost' => 3.33,
                'parking_cost' => 10,
                'total_cost' => 20 + 3.33 + 10
            ],
        ]);
    }
}
