<?php

namespace AssetManger;

use App\Http\Controllers\ManufacturerController;
use App\Jobs\RoleBoot;
use App\Models\Manufacturer;
use App\Models\User;
use Tests\TestCase;
use function action;

class UpdateManufacturerAuthTest extends TestCase {

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
            "supportUrl" => $manufacturer->supportUrl,
            "supportEmail" => $manufacturer->supportEmail,
            "photoId" => $manufacturer->photoID,
        ])->assertRedirect(action([ManufacturerController::class, 'show'], $manufacturer->id))
            ->assertSessionHas('success_message');
//
        $this->assertNotEquals('title for Updating', $manufacturer->name);

// updates the title of the post
        $this->actingAs($user)->put(action([ManufacturerController::class, 'update'], $manufacturer->id), [
            "name" => 'updated',
            "supportUrl" => $manufacturer->supportUrl,
            "supportEmail" => $manufacturer->supportEmail,
            "photoId" => $manufacturer->photoID,
        ]);
        //see's if the post was actually updated
        $this->assertEquals('Updated', $manufacturer->refresh()->name);
    }

}
