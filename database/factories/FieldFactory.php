<?php

namespace Database\Factories;

use App\Models\Field;
use Illuminate\Database\Eloquent\Factories\Factory;

class FieldFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Field::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=>$this->faker->domainWord,
            "type"=>"select",
            "format"=>"alpha",
            "help"=>$this->faker->sentence,
            "required"=>$this->faker->numberBetween([0],[1]),
        ];
    }
}
