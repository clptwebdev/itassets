<?php

namespace Database\Seeders;

use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Comment;
use App\Models\Component;
use App\Models\Consumable;
use App\Models\Field;
use App\Models\Fieldset;
use App\Models\Location;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(5)->create();
        \App\Models\Asset::factory(5)->create();
        \App\Models\Supplier::factory(5)->create();
        \App\Models\Location::factory(5)->create();
        \App\Models\Manufacturer::factory(5)->create();
        \App\Models\AssetModel::factory(5)->create();
        \App\Models\Field::factory(5)->create();
        \App\Models\Fieldset::factory(5)->create();
        \App\Models\Component::factory(5)->create();
        \App\Models\Accessory::factory(5)->create();
        \App\Models\Status::factory(5)->create();
        \App\Models\Comment::factory(5)->create();
        \App\Models\Consumable::factory(5)->create();
        \App\Models\Miscellanea::factory(5)->create();
        for($i = 0; $i < 5; $i++)
        {
            DB::table('field_fieldset')->insert(
                [
                    'field_id' => Field::select('id')->orderByRaw("RAND()")->first()->id,
                    'fieldset_id' => Fieldset::select('id')->orderByRaw("RAND()")->first()->id,
                ]
            );
        }
        for($i = 0; $i < 5; $i++)
        {
            DB::table('location_user')->insert(
                [
                    'location_id' => Location::select('id')->orderByRaw("RAND()")->first()->id,
                    'user_id' => User::select('id')->orderByRaw("RAND()")->first()->id,
                ]
            );
        }
        $array = ['asset', 'App\Models\Consumable', 'App\Models\accessory', 'App\Models\component'];
        for($i = 0; $i < 5; $i++)
        {

            DB::table('commentables')->insert(
                [
                    'comment_id' => Comment::select('id')->orderByRaw("RAND()")->first()->id,
                    'commentables_type' => $array[array_rand($array)],
                    'commentables_id' => rand(1, 5),
                ]
            );
        }


    }

}
