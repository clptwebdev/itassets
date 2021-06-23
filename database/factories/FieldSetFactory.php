<?php

namespace Database\Factories;

use App\Models\FieldSet;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldSetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FieldSet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=>$this->faker->randomElement(["Laptop"]),
        ];
    }
}
