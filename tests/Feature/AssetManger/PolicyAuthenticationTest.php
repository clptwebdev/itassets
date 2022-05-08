<?php

namespace AssetManger;

use App\Http\Controllers\UserController;
use App\Jobs\RoleBoot;
use App\Models\User;
use Tests\TestCase;
use function action;

class PolicyAuthenticationTest extends TestCase {

    public function test_User_Can_Only_Edit_Their_Profile()
    {
        $this->withoutExceptionHandling();
        RoleBoot::dispatch();

        //Create User Guest
        $users = User::factory(['role_id' => 6])->count(1)->create();
        $global = User::factory(['role_id' => 1])->count(1)->create();
        $guest = $users->first();
        $globalUser = $global->first();

        //Check it was created
        $this->assertCount(1, $users);
        $this->assertCount(1, $global);

        //Check it was Set to Role user
        $this->assertDatabaseHas(User::class, [
            'role_id' => 6,
        ]);
        $this->assertDatabaseHas(User::class, [
            'role_id' => 1,
        ]);
        //Check Users can access pages with correct permissions
        $this->actingAs($globalUser)->get(action([UserController::class, 'edit'], $globalUser->id))->assertViewIs('users.edit')->assertSuccessful();
        $this->actingAs($globalUser)->get(action([UserController::class, 'edit'], $guest->id))->assertViewIs('users.edit')->assertSuccessful();
        $this->actingAs($guest)->get(action([UserController::class, 'edit'], $globalUser->id))->assertViewIs('errors.403')->assertSuccessful();;
    }

}
