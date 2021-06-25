<?php

namespace Database\Factories;

use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

class ManufacturerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manufacturer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name"=>$this->faker->company,
            "supportUrl"=>$this->faker->url,
            "supportPhone"=>$this->faker->unique()->phoneNumber,
            "supportEmail"=>$this->faker->unique()->safeEmail,
            "photoId"=>$this->faker->unique()->imageUrl()
        ];
    }
}
