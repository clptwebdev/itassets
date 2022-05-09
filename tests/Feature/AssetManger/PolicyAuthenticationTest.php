<?php

namespace AssetManger;

use App\Http\Controllers\AssetController;
use App\Http\Controllers\UserController;
use App\Jobs\RoleBoot;
use App\Models\Asset;
use App\Models\User;
use Closure;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use function action;

class PolicyAuthenticationTest extends TestCase {

    /**
     * @test
     * @dataProvider requests
     */
    public function test_Guests_Are_Not_Allowed(Closure $sendRequest)
    {
        RoleBoot::dispatch();

        $this->login(User::factory(['role_id' => 7])->create());

        $asset = Asset::factory()->create();
        /** @var TestResponse $response */
        $response = $sendRequest->call($this, $asset);

        $response->assertForbidden();

    }

    /**
     * @test
     * @dataProvider requests
     */
    public function test_Global_Admin_Are_Allowed(Closure $sendRequest)
    {
        RoleBoot::dispatch();

        $this->login(User::factory(['role_id' => 1])->create());

        $asset = Asset::factory()->create();
        /** @var TestResponse $response */
        $response = $sendRequest->call($this, $asset);

        //assert success
        $this->assertTrue(in_array($response->status(), [200, 302]));

    }

    public function requests(): \Generator
    {

        yield [fn(Asset $asset) => $this->get(action([AssetController::class, 'index']))];
        yield [fn(Asset $asset) => $this->get(action([AssetController::class, 'create']))];
        yield [fn(Asset $asset) => $this->get(action([AssetController::class, 'store']))];

    }

    public function test_User_Can_Only_Edit_Their_Profile()
    {
        //old duplicated way of testing auth check above for dynamic way

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
        $this->actingAs($guest)->get(action([UserController::class, 'edit'], $globalUser->id))->assertViewIs('errors.403')->assertForbidden();
    }

}
