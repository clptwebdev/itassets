<?php

namespace AssetManger;

use App\Http\Controllers\ManufacturerController;
use App\Jobs\RoleBoot;
use App\Models\Manufacturer;
use App\Models\User;
use Tests\TestCase;

class UpdateManufacturerAuth extends TestCase {

    public function test_Update_Auth_Manufacturers()
    {
        $this->withoutExceptionHandling();
        RoleBoot::dispatch();
//create a user for auth or check base test case custom function for Homemade login function
        $user = User::factory(
            ['role_id' => '1'])->create();

        $manufacturer = Manufacturer::factory()->create();

        $this->actingAs($user)->put(action([ManufacturerController::class, 'update'], $manufacturer->id), [
            "name" => 'title for Updating',
            "supportPhone" => $manufacturer->supportPhone,
            "supportUrl" => $manufacturer->supportUrl,
            "supportEmail" => $manufacturer->supportEmail,
            "photoId" => $manufacturer->photoID,
        ])->assertRedirect(action([ManufacturerController::class, 'index']))
            ->assertSessionHas('success_message');

        $this->assertNotEquals('title for Updating', $manufacturer->name);

// updates the title of the post
        $this->actingAs($user)->put(action([ManufacturerController::class, 'update'], $manufacturer->id), [
            "name" => 'title for Updating',
            "supportPhone" => $manufacturer->supportPhone,
            "supportUrl" => $manufacturer->supportUrl,
            "supportEmail" => $manufacturer->supportEmail,
            "photoId" => $manufacturer->photoID,
        ]);
        //see's if the post was actually updated
        $this->assertEquals('title for Updating', $manufacturer->refresh()->name);
    }

}
