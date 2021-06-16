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
        \App\Models\Asset::factory(10)->create();
        \App\Models\Supplier::factory(10)->create();
        \App\Models\Location::factory(10)->create();
        \App\Models\Manufacturer::factory(10)->create();
    }
}
