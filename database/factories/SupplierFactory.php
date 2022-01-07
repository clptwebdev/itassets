<?php

namespace Database\Factories;

use App\Models\Photo;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     * @var string
     */
    protected $model = Supplier::class;

    /**
     * Define the model's default state.
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->unique()->company,
            "address_1" => $this->faker->address,
            "city" => $this->faker->city,
            "county" => $this->faker->country,
            "postcode" => $this->faker->numerify(),
            "telephone" => $this->faker->numberBetween([102373633], [92373633]),
            "fax" => $this->faker->creditCardNumber,
            "email" => $this->faker->safeEmail,
            "photo_id" => Photo::factory("photo_id"),
            "notes" => $this->faker->paragraph,
        ];
    }

}
