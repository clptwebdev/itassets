<?php

namespace AssetManger;

use App\Http\Controllers\RequestsController;
use App\Jobs\RoleBoot;
use App\Models\Asset;
use App\Models\Location;
use App\Models\Requests;
use App\Models\Transfer;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;
use function action;

class TransferPermissionTest extends TestCase {

    public function test_transfer_without_permission()
    {

        $this->withoutExceptionHandling();
        RoleBoot::dispatch();
        $notAdmin = User::factory(['role_id' => 7])->create();
        //create location x2
        $locationOne = Location::factory()->create();
        $locationTwo = Location::factory(1)->create();
        $this->actingAs($notAdmin)->get('/assets')
            ->assertForbidden();
        //create a new asset
        $asset2 = Asset::factory(1)->create(['location_id' => $locationTwo->first()->id])->first();
        //do transfer logic again see if automatically goes through or not
        $this->actingAs($notAdmin)->post(action([RequestsController::class, 'transfer']), [
            'asset_tag' => $asset2->asset_tag,
            'type' => 'transfer',
            'model_type' => 'asset',
            'model_id' => $asset2->first()->id,
            'location_to' => $locationOne->first()->id,
            'location_from' => $locationTwo->first()->id,
            'notes' => 'Test Transfer',
            'user_id' => $notAdmin->id,
            'transfer_date' => Carbon::now(),
        ])
            ->assertRedirect('/assets')
            ->assertSessionHas('success_message');
//check is status is not approved
        $this->assertDatabaseHas(Requests::class, [
            'type' => 'transfer',
            'model_type' => 'asset',
            'model_id' => $asset2->first()->id,
            'location_to' => $locationOne->first()->id,
            'location_from' => $locationTwo->first()->id,
            'notes' => 'test transfer',
            'user_id' => $notAdmin->id,
            //not approved
            'status' => 0,
        ]);
        //check database does not have a transfer item yet as this shouldn't have been approved
        $this->assertDatabaseMissing(Transfer::class, [
            'model_type' => 'asset',
            'notes' => 'test transfer',
            'model_id' => $asset2->first()->id,
            'location_to' => $locationOne->first()->id,
            'location_from' => $locationTwo->first()->id,
        ]);
        //make sure database is yet to change
        $this->assertDatabaseHas(Asset::class, [
            'asset_tag' => $asset2->asset_tag,
            'location_id' => $locationTwo->first()->id,
        ]);
    }

    public function test_transfer_with_permission()
    {
        //This Test will check to see if the user need to request permission on transfer
        $this->withoutExceptionHandling();
        RoleBoot::dispatch();
        $user = $this->login();

        $this->actingAs($user)->get('/assets')
            ->assertSuccessful();
        //create location x2
        $locationOne = Location::factory()->create();
        $locationTwo = Location::factory(1)->create();
        //create asset
        $asset = Asset::factory(1)->create(['location_id' => $locationOne->id]);
        $this->assertCount(1, $asset);
        //do transfer logic
        $this->actingAs($user)->post(action([RequestsController::class, 'transfer']), [
            'asset_tag' => '222',
            'type' => 'transfer',
            'model_type' => 'asset',
            'model_id' => $asset->first()->id,
            'location_to' => $locationTwo->first()->id,
            'location_from' => $locationOne->first()->id,
            'notes' => 'Test Transfer',
            'user_id' => $user->id,
            'transfer_date' => Carbon::now(),
        ])
            ->assertRedirect('/assets')
            ->assertSessionHas('success_message');
        //check database has requests
        $this->assertDatabaseHas(Requests::class, [
            'id' => 1,
            'type' => 'transfer',
            'model_type' => 'asset',
            'model_id' => $asset->first()->id,
            'location_to' => $locationTwo->first()->id,
            'location_from' => $locationOne->first()->id,
            'notes' => 'test transfer',
            'user_id' => $user->id,
            "super_id" => $user->id,
            'status' => 1,
        ]);
        $this->assertDatabaseHas(Transfer::class, [
            'model_type' => 'asset',
            'notes' => 'test transfer',
            'model_id' => $asset->first()->id,
            'location_to' => $locationTwo->first()->id,
            'location_from' => $locationOne->first()->id,
        ]);
        $this->assertDatabaseHas(Asset::class, [
            'asset_tag' => '222',
            'location_id' => $locationTwo->first()->id,
        ]);

    }

}
