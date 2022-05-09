<?php

namespace Tests;

use App\Http\Controllers\UserController;
use App\Jobs\AssetPdf;
use App\Jobs\RoleBoot;
use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Bus;

abstract class TestCase extends BaseTestCase {

    use RefreshDatabase;
    use CreatesApplication;

    protected function setUp(): void
    {
        Parent::setUp();

        Bus::fake([AssetPdf::class]);
    }

    public function login(User $user = null): User
    {

        $user ??= $user = User::factory()->create([
            'role_id' => 1,
        ]);
        $this->actingAs($user);

        return $user;
    }

    public function createLocationRelationships(User $user, int $amount = 3): User
    {
        $locations = Location::factory()->count($amount)->create();
        $arr = [];
        foreach($locations as $location)
        {
            $arr[] = $location->id;
        }
        $locationString = implode(',', $arr);

        $this->actingAs($user)->put(action([UserController::class, 'update'], $user->id), [
            'name' => $user->name,
            'email' => 'elliot.putt@clpt.co.uk',
            'role_id' => $user->role_id,
            'permission_ids' => $locationString,
        ]);

        return $user;
    }

    public function assignLocationsRelationship(array $locationArray, User $user): User
    {
        $locations = Location::whereIn('id', $locationArray)->get();

        $arr = [];
        foreach($locations as $location)
        {
            $arr[] = $location->id;
        }
        $locationString = implode(',', $arr);

        $this->patch(action([UserController::class, 'update'], $user->id), [
            'name' => $user->name,
            'email' => 'elliot.putt@clpt.co.uk',
            'role_id' => $user->role_id,
            'permission_ids' => $locationString,
        ]);

        return $user->refresh();

    }

}
