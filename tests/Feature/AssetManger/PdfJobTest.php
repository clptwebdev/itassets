<?php

namespace AssetManger;

use App\Jobs\AssetPdf;
use App\Jobs\RoleBoot;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use function route;

class PdfJobTest extends TestCase {

    public function test_Pdf_Job_Is_Dispatched()
    {
        $this->withoutExceptionHandling();
        $this->travelTo(Carbon::make('2022-01-01 00:00:00'));
        RoleBoot::dispatch();

        $locations = Location::factory()->count(1)->create();
        $global = User::factory([
            'role_id' => 1,
            'location_id' => $locations->first()->id,
        ])->count(1)->create();

        $globalUser = $global->first();

        $this->login($globalUser);

        $assets = Asset::factory([
            'location_id' => $locations->first()->id,
        ])->count(1)->create();

        $this->assertCount(1, $assets);

        $asset = $assets->first();

        Bus::fake();

        // This may get a 302 as locationUser the view policy need to have locations from an array
        $this->actingAs($globalUser)->get(route('asset.showPdf', $asset->id))
            ->assertRedirect(route('assets.show', $asset->id))
            ->assertSessionHas('success_message');

        Bus::assertDispatched(AssetPdf::class);

    }

//    public function test_Pdf_Job_Is_File_Generated()
//    {

//        $this->withoutExceptionHandling();
//        RoleBoot::dispatch();

//    }

}
