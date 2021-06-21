<?php

namespace Database\Factories;

use App\Models\AssetModel;
use App\Models\Fieldset;
use App\Models\Manufacturer;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetModelFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetModel::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "manufacturer_id"=>Manufacturer::factory("manufacturer_id"),
            "name"=>$this->faker->name,
            "model_no"=>$this->faker->numerify(),
            "depreciation_id"=>$this->faker->randomDigit(),
            "eol"=>$this->faker->randomDigit,
            "notes"=>$this->faker->paragraph,
            "fieldset_id"=>Fieldset::factory("fieldset_id"),
            "photo_id"=>Photo::factory()->faker->randomDigit,
        ];
    }
}
