<?php

namespace AssetManger;

use App\Http\Controllers\ManufacturerController;
use App\Models\Manufacturer;
use App\Models\User;
use Tests\TestCase;

class ManufacturerCreateForm extends TestCase {

    public function test_Manufacturer_Can_Create()
    {
        $this->withoutExceptionHandling();
//        uses an auth user because you need to be logged in
        $user = User::factory(
            ['role_id' => '1'])->create();
        //Creates a manufacturer and confirms it redirects
        $this->actingAs($user)->post(action([ManufacturerController::class, 'store']), [
            "name" => 'Testing123',
            "supportPhone" => '07875973958',
            "supportUrl" => 'www.google.com',
            "supportEmail" => 'ejputt67@gmail.com',
            "photoId" => 1,
        ])
            ->assertRedirect(action([ManufacturerController::class, 'index']))
            ->assertSessionHas('success_message');
        //checks to see if it's in the database
        $this->assertDatabaseHas(Manufacturer::class, [
            "name" => 'Testing123',
            "supportPhone" => '07875973958',
            "supportUrl" => 'www.google.com',
            "supportEmail" => 'ejputt67@gmail.com',
            "photoId" => 1,
        ]);

        //refactored
        $user = User::factory(
            ['role_id' => '1'])->create();
        //Creates a manufacturer and confirms it redirects
      $sendRequest = fn() =>  $this->actingAs($user)->post(action([ManufacturerController::class, 'store']), [
          "name" => 'resting123',
          "supportPhone" => '07335443958',
          "supportUrl" => 'www.gooogle.com',
          "supportEmail" => 'ejput4t67@gmail.com',
          "photoId" => 1,
        ]);
            $sendRequest()
                ->assertRedirect(action([ManufacturerController::class, 'index']))
                ->assertSessionHas('success_message');
        //checks to see if it's in the database

        $this->assertDatabaseHas(Manufacturer::class, [
            "name" => 'resting123',
            "supportPhone" => '07335443958',
            "supportUrl" => 'www.gooogle.com',
            "supportEmail" => 'ejput4t67@gmail.com',
            "photoId" => 1,
        ]);

    }

}
