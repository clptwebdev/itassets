<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "asset_model"=> AssetModel::factory('model_id'),
            "asset_tag"=>$this->faker->unique()->randomNumber(),
            "name"=>$this->faker->unique()->name,
            "serial_no"=>$this->faker->numberBetween([1000],[9000]),
            "status_id"=>Status::factory("status_id"),
            "purchased_date"=>$this->faker->dateTimeThisYear(),
            "purchased_cost"=>$this->faker->numberBetween([5],[1000]),
            "donated"=> 1,
            "supplier_id"=>Supplier::factory("suppliers_id"),
            "order_No"=>$this->faker->numerify(),
            "warranty"=>$this->faker->numberBetween([0],[5])." Years",
            "location_id"=>Location::factory("locations_id"),
            "room"=>"205",
            "user_id"=>User::factory("user_id"),
            "audit_date"=>$this->faker->date() ?? null,
            "notes"=> $this->faker->paragraph()



        ];
    }
}
