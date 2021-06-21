<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Fieldset;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Asset::factory(20)->create();
        \App\Models\Supplier::factory(20)->create();
        \App\Models\Location::factory(20)->create();
        \App\Models\Manufacturer::factory(20)->create();
        \App\Models\AssetModel::factory(20)->create();
        \App\Models\Field::factory(20)->create();
        \App\Models\Fieldset::factory(20)->create();
        for ($i = 0; $i < 10; $i++) {
        DB::table('field_fieldset')->insert(
            [
                'field_id' => Field::select('id')->orderByRaw("RAND()")->first()->id,
                'fieldset_id' =>Fieldset::select('id')->orderByRaw("RAND()")->first()->id,
            ]
        );
        }
    }
}
