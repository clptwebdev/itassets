<?php

namespace Database\Factories;

use App\Models\accessory;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class accessoryFactory extends Factory {

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = accessory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "serial_no" => $this->faker->randomNumber(),
            "name" => $this->faker->name,
            "status_id" => Status::factory("status_id"),
            "purchased_date" => $this->faker->date(),
            "purchased_cost" => $this->faker->randomNumber(),
            "supplier_id" => Supplier::factory("supplier_id"),
            "manufacturer_id" => Manufacturer::factory("manufacturer_id"),
            "order_no" => $this->faker->randomNumber(),
            "warranty" => $this->faker->numberBetween([0], [5]),
            "location_id" => Location::factory("location_id"),
            "user_id" => User::factory("id"),
            "notes" => $this->faker->paragraph,
        ];
    }

}
