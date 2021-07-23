<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('login/microsoft', 'App\Http\Controllers\OfficeLoginController@redirectToProvider');
Route::get('login/microsoft/callback', 'App\Http\Controllers\OfficeLoginController@handleProviderCallback');

require __DIR__.'/auth.php';

Route::group(['middleware'=>'auth'], function(){
    Route::get('/', function(){
        return view('dashboard',
            [
                'locations' => auth()->user()->locations,
            ]
        );
    })->name('home');

    Route::get('/dashboard', function(){
        return view('dashboard',
            [
                'locations' => auth()->user()->locations,
            ]
        );
    })->name('dashboard');



    //Administrator Permissions Middleware
    Route::group(['middleware'=>'role:1'], function(){
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::resource('/category', 'App\Http\Controllers\CategoryController');
        Route::resource('/users', 'App\Http\Controllers\UserController');
        Route::resource('/supplier', 'App\Http\Controllers\SupplierController');
        Route::resource('/photo', 'App\Http\Controllers\PhotoController');
        Route::resource('/asset-models', 'App\Http\Controllers\AssetModelController');
        Route::resource('/fieldsets', 'App\Http\Controllers\FieldsetController');
        Route::resource('/fields', 'App\Http\Controllers\FieldController');
        Route::post('photo/upload', 'App\Http\Controllers\PhotoController@upload');
        Route::resource('/assets', 'App\Http\Controllers\AssetController');
        Route::post('/assets/filter', 'App\Http\Controllers\AssetController@filter')->name('assets.filter');
        Route::get('/status/{status}/assets', 'App\Http\Controllers\AssetController@status')->name('assets.status');
        Route::resource('/status', 'App\Http\Controllers\StatusController');
        Route::resource('/components', 'App\Http\Controllers\ComponentController');
        Route::resource('/accessories', 'App\Http\Controllers\AccessoryController');
        Route::resource('/consumables', 'App\Http\Controllers\ConsumableController');




        Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');
//      exports
        Route::get("/exportassets", [\App\Http\Controllers\AssetController::class, "export"]);
        Route::get("/exportconsumables", [\App\Http\Controllers\ConsumableController::class, "export"]);
        Route::get("/exportlocations", [\App\Http\Controllers\LocationController::class, "export"]);
        Route::get("/exportmanufacturers", [\App\Http\Controllers\ManufacturerController::class, "export"]);
        Route::get("/exportsuppliers", [\App\Http\Controllers\SupplierController::class, "export"]);
        Route::get("/exportusers", [\App\Http\Controllers\UserController::class, "export"]);
        Route::get("/exportcomponents", [\App\Http\Controllers\ComponentController::class, "export"]);
        Route::get("/exportaccessories", [\App\Http\Controllers\AccessoryController::class, "export"]);
//
        Route::post("/importmanufacturer", [\App\Http\Controllers\ManufacturerController::class, "import"]);
        Route::post("/importcomponents", [\App\Http\Controllers\ComponentController::class, "import"]);
        Route::post("/importacessories", [\App\Http\Controllers\AccessoryController::class, "import"]);
        Route::post("/importconsumables", [\App\Http\Controllers\ConsumableController::class, "import"]);
        Route::Post("components/create/ajax", [\App\Http\Controllers\ComponentController::class, "ajaxMany"]);
        Route::Post("accessories/create/ajax", [\App\Http\Controllers\AccessoryController::class, "ajaxMany"]);
        Route::Post("consumables/create/ajax", [\App\Http\Controllers\ConsumableController::class, "ajaxMany"]);
        Route::Post("components/export-import-errors", [\App\Http\Controllers\ComponentController::class, "importErrors"])->name("componentexport.import");
        Route::Post("accessories/export-import-errors", [\App\Http\Controllers\AccessoryController::class, "importErrors"])->name("accessoryexport.import");
        Route::Post("consumables/export-import-errors", [\App\Http\Controllers\ConsumableController::class, "importErrors"])->name("consumableexport.import");


        Route::post("/importassets", [\App\Http\Controllers\AssetController::class, "import"]);
        Route::Post("/export-import-errors", [\App\Http\Controllers\AssetController::class, "importErrors"])->name("export.import");
        Route::Post("assets/create/ajax", [\App\Http\Controllers\AssetController::class, "ajaxMany"]);


        Route::get("manufacturers", [\App\Http\Controllers\ManufacturerController::class, "show"]);
        Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "create"]);
        Route::get("manufacturers/edit/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "edit"]);
        Route::Put("manufacturers/edit/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "update"]);
        Route::delete("manufacturers/delete/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "destroy"]);
        Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "list"]);
        Route::Post("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "store"]);
        Route::Post("manufacturers/create/import", [\App\Http\Controllers\ManufacturerController::class, "createMany"]);
        Route::Post("manufacturers/create/ajax", [\App\Http\Controllers\ManufacturerController::class, "ajaxMany"]);

        Route::get('chart/pie/locations', 'App\Http\Controllers\ChartController@getPieChart');

        //
    });
});
