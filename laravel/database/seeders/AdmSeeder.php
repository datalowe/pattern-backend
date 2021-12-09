<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Adm;

class AdmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Adm::insert([
            [
                'username' => 'admin1',
            ],
            [
                'username' => 'admin2'
            ]
        ]);
    }
}
