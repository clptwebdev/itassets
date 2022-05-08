<?php

namespace AssetManger;

use App\Http\Controllers\ManufacturerController;
use App\Jobs\RoleBoot;
use App\Models\Manufacturer;
use App\Models\User;
use Tests\TestCase;
use function action;

class ManufacturerCreateFormTest extends TestCase {

    public function test_Manufacturer_Can_Create()
    {
        $this->withoutExceptionHandling();

        RoleBoot::dispatch();
//        uses an auth user because you need to be logged in
        $user = User::factory(
            ['role_id' => '1'])->create();
        //Creates a manufacturer and confirms it redirects
        $this->actingAs($user)->post(action([ManufacturerController::class, 'store']), [
            "name" => 'Testing123',
            "supportPhone" => '3',
            "supportUrl" => 'www.google.com',
            "supportEmail" => 'ejputt67@gmail.com',
            "photoId" => 1,
        ])
            ->assertRedirect(action([ManufacturerController::class, 'index']))
            ->assertSessionHas('success_message');
//        updates and checks for update errors
        $manufacturer = Manufacturer::whereName('testing123')->first();

        $this->actingAs($user)->patch(action([ManufacturerController::class, 'update'], $manufacturer->id), [
            "name" => 'Testing123',
            "supportPhone" => '01234321',
            "supportUrl" => $manufacturer->supportUrl,
            "supportEmail" => $manufacturer->supportEmail,
            "photoId" => $manufacturer->photoId,
        ])
            ->assertRedirect(action([ManufacturerController::class, 'show'], $manufacturer->id))
            ->assertSessionHas('success_message');
//        checks to see if it's in the databaseS
        $this->assertDatabaseHas(Manufacturer::class, [
            "name" => 'testing123',
            "supportPhone" => '01234321',
            "supportUrl" => 'www.google.com',
            "supportEmail" => 'ejputt67@gmail.com',
            "photoId" => 1,
        ]);

        //refactored
        $user = User::factory(
            ['role_id' => '1'])->create();
        //Creates a manufacturer and confirms it redirects
        $sendRequest = fn() => $this->actingAs($user)->post(action([ManufacturerController::class, 'store']), [
            "name" => 'resting123',
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
            "supportUrl" => 'www.gooogle.com',
            "supportEmail" => 'ejput4t67@gmail.com',
            "photoId" => 1,
        ]);

    }

}
