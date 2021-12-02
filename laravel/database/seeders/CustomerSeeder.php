<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Customer::insert([
            [
                'username' => 'customer1',
                'token' => 'customer-token1'
            ],
            [
                'username' => 'customer2',
                'token' => 'customer-token2'
            ],
            [
                'username' => 'customer3',
                'token' => 'customer-token3'
            ],
        ]);
    }
}
