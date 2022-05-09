<?php

namespace AssetManger;

use App\Jobs\AssetPdf;
use App\Jobs\RoleBoot;
use App\Models\Asset;
use App\Models\Location;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Dispatcher;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use function route;

class PdfJobTest extends TestCase {

    public function test_Pdf_Job_Is_Dispatched()
    {
        $this->withoutExceptionHandling();
        $this->travelTo(Carbon::make('2022-01-01 00:00:00'));
        RoleBoot::dispatch();

        $locations = Location::factory()
            ->count(1)
            ->create();

        $global = User::factory([
            'role_id' => 1,
            'location_id' => $locations->first()->id,
        ])->count(1)->create();

        $assets = Asset::factory([
            'location_id' => $locations->first()->id,
        ])->count(1)->create();

        $this->assertCount(1, $global);
        $this->assertCount(1, $locations);
        $this->assertCount(1, $assets);

        $globalUser = $global->first();
        $asset = $assets->first();
        $this->login($globalUser);

        Bus::fake();
        //check report is missing
        $this->assertDatabaseMissing(Report::class, [
            'user_id' => $globalUser->id,
        ]);

        // This may get a 302 as locationUser the view policy need to have locations from an array
        $this->actingAs($globalUser)->get(route('asset.showPdf', $asset->id))
            ->assertRedirect(route('assets.show', $asset->id))
            ->assertSessionHas('success_message');

        Bus::assertDispatched(AssetPdf::class);
        //check report creation
        $this->assertDatabaseHas(Report::class, [
            'user_id' => $globalUser->id,
        ]);
      

    }

    public function test_Pdf_Job_Is_File_Generated()
    {
        //This test will run slow as it actually runs the job
        $this->withoutExceptionHandling();
        $this->travelTo(Carbon::make('2022-01-01 00:00:00'));
        RoleBoot::dispatch();
        Bus::swap(app(Dispatcher::class));
        Storage::fake('public');

        $locations = Location::factory()
            ->count(1)
            ->create();

        $global = User::factory([
            'role_id' => 1,
            'location_id' => $locations->first()->id,
        ])->count(1)->create();

        $assets = Asset::factory([
            'location_id' => $locations->first()->id,
        ])->count(1)->create();

        $this->assertCount(1, $global);
        $this->assertCount(1, $locations);
        $this->assertCount(1, $assets);

        $globalUser = $global->first();
        $asset = $assets->first();
        $this->login($globalUser);

        //check report is missing
        $this->assertDatabaseMissing(Report::class, [
            'user_id' => $globalUser->id,
        ]);

        // This may get a 302 as locationUser the view policy need to have locations from an array
        $this->actingAs($globalUser)->get(route('asset.showPdf', $asset->id))
            ->assertRedirect(route('assets.show', $asset->id))
            ->assertSessionHas('success_message');

        $this->assertDatabaseHas(Report::class, [
            'user_id' => $globalUser->id,
        ]);

        //setting parameters
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        //Checks Storage for file
        Storage::disk()->assertExists('/public/reports/asset-' . $asset->asset_tag . '-' . $date . '.pdf');

    }

}
