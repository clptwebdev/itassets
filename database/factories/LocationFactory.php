<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use phpDocumentor\Reflection\Types\Null_;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "photo_id"=>$this->faker->numberBetween([0],[1000]),
            "icon"=>$this->faker->hexColor,
            "name"=>$this->faker->company,
            "address_1"=>$this->faker->address,
            "address_2"=>$this->faker->address ?? Null,
            "city"=>$this->faker->city,
            "county"=>$this->faker->country,
            "post_code"=>$this->faker->postcode,
            "telephone"=>$this->faker->numberBetween([102373633],[92373633]),
            "email"=>$this->faker->safeEmail,
        ];
    }
}
