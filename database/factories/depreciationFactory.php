<?php

namespace Database\Factories;

use App\Models\depreciation;
use Illuminate\Database\Eloquent\Factories\Factory;

class depreciationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = depreciation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=>$this->faker->name,
            "years"=>$this->faker->randomNumber(),
        ];
    }
}
