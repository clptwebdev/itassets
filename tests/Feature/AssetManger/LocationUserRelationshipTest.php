<?php

namespace AssetManger;

use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\UserController;
use App\Jobs\RoleBoot;
use App\Models\Accessory;
use App\Models\Asset;
use App\Models\Location;
use App\Models\LocationUser;
use App\Models\Permission;
use App\Models\Supplier;
use App\Models\User;
use Tests\TestCase;
use Webmozart\Assert\Assert;

class LocationUserRelationshipTest extends TestCase {

    public function test_User_Has_Assigned_Locations()
    {
        $this->withoutExceptionHandling();
        RoleBoot::dispatch();

        $global = $this->login();
        //custom function to create locations and assign them to a user. This requires a user. an additional parameter can be set for the amount of locations
        $this->createLocationRelationships($global);

        $this->assertNotFalse($global->locations()->get());
        $this->assertNotEmpty($global->locations()->get());

    }

    public function test_Can_A_User_Access_An_Asset_Without_Being_Assigned_The_Location()
    {

        RoleBoot::dispatch();
        $user = $this->login();
        $locations = Location::factory()->count(1)->create();
        $locationItem = $locations->first();
        $locationArray = $locations->pluck('id')->toArray();
        LocationUser::query()->delete();
        $accessories = Accessory::factory(['location_id' => $locationItem->id])->count(1)->create();
        $accessory = $accessories->first();

        $this->assertDatabaseHas(Accessory::class, [
            'location_id' => $locationItem->id,
        ]);
        $this->assertCount(1, $locations);

        $this->get(action([AccessoryController::class, 'show'], $accessory->id))->assertViewIs('errors.403')->assertForbidden();

        //custom function to assign locations to a user. This requires a user and a array of locationID's
        $this->assignLocationsRelationship($locationArray, $user);

        $this->get(route('accessories.show', $accessory->id))->assertViewIs('accessory.show')->assertStatus(200)->assertSuccessful();

    }

}
