<?php

namespace AssetManger;

use App\Exports\ManufacturerExport;
use App\Http\Controllers\ManufacturerController;
use App\Jobs\RoleBoot;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportExport extends TestCase {

    public function test_file_import_fail()
    {
        ////////////////////////////////////////////
        /////// Test For Import Manufacturers //////
        ////////////////////////////////////////////
        $this->withoutExceptionHandling();
        RoleBoot::dispatch();
        $user = $this->login();
        $file = asset('storage/testUploadCsv/manufacturer.csv');
        $files = UploadedFile::fake()->createWithContent('file.csv', $file);
        $this->actingAs($user)->post(action([ManufacturerController::class, 'import']), [
            'csv' => $files,
        ])->assertRedirect('/manufacturers')->assertSessionHas('danger_message');
    }

    public function test_file_export_working()
    {
        ////////////////////////////////////////////
        /////////// Sets environment time //////////
        ////////////////////////////////////////////
        $this->travelTo(Carbon::make('2022-01-01 00:00:00'));
        RoleBoot::dispatch();
        $user = $this->login();
        Excel::fake();
        $this->actingAs($user)->get(action([ManufacturerController::class, 'export'])
        )->assertRedirect('/manufacturers')->assertSessionHas('success_message');
        ////////////////////////////////////////////
        ///////// Checks storage for file //////////
        ////////////////////////////////////////////
        Excel::assertStored("/public/csv/manufacturers-ex-01-01-22-0000.xlsx", function(ManufacturerExport $export) {
            return true;
        });
    }

}
