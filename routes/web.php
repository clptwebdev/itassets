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

Route::get('/', function(){
    if(auth()->user()->role_id == 1){
        $locations = \App\Models\Location::all();
        $assets = \App\Models\Asset::all();
    }else{
        $locations = auth()->user()->locations;
        $assets = auth()->user()->location_assets;
    }
    return view('dashboard',
        [
            'locations' => $locations,
            'assets' => $assets,
        ]
    );
})->name('home');

Route::get('/dashboard', function(){
    if(auth()->user()->role_id == 1){
        $locations = \App\Models\Location::all();
        $assets = \App\Models\Asset::all();
    }else{
        $locations = auth()->user()->locations;
        $assets = auth()->user()->location_assets;
    }
    return view('dashboard',
        [
            'locations' => $locations,
            'assets' => $assets,
        ]
    );
})->name('dashboard');


Route::get('login/microsoft', 'App\Http\Controllers\OfficeLoginController@redirectToProvider');
Route::get('login/microsoft/callback', 'App\Http\Controllers\OfficeLoginController@handleProviderCallback');

require __DIR__.'/auth.php';

Route::group(['middleware'=>'auth'], function(){
    
    //Super Admmin

    //Super Admin or Admin
    Route::group(['middleware'=>'admin.role'], function(){
        Route::resource('/users', 'App\Http\Controllers\UserController');
        Route::get('/user/permissions', 'App\Http\Controllers\UserController@userPermissions')->name('user.permissions');
    });

    //User Manager

    //User


    //Administrator Permissions Middleware
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::resource('/comment', 'App\Http\Controllers\CommentController');
        Route::resource('/category', 'App\Http\Controllers\CategoryController');
        Route::post('permissions/users', 'App\Http\Controllers\UserController@permissions');
        Route::resource('/supplier', 'App\Http\Controllers\SupplierController');
        Route::resource('/photo', 'App\Http\Controllers\PhotoController');
        Route::resource('/asset-models', 'App\Http\Controllers\AssetModelController');
        Route::resource('/depreciation', 'App\Http\Controllers\DepreciationController');
        Route::resource('/fieldsets', 'App\Http\Controllers\FieldsetController');
        Route::resource('/fields', 'App\Http\Controllers\FieldController');
        Route::post('photo/upload', 'App\Http\Controllers\PhotoController@upload');
    // Asset Routes
        Route::resource('/assets', 'App\Http\Controllers\AssetController');
        Route::post('/assets/filter', 'App\Http\Controllers\AssetController@filter')->name('assets.filter');
        Route::get('/status/{status}/assets', 'App\Http\Controllers\AssetController@status')->name('assets.status');
        Route::get('/location/{location}/assets', 'App\Http\Controllers\AssetController@location')->name('assets.location');
        Route::post('/assets/pdf', 'App\Http\Controllers\AssetController@downloadPDF')->name('assets.pdf');
        Route::get('/asset/{asset}/pdf', 'App\Http\Controllers\AssetController@downloadShowPDF')->name('asset.showPdf');
        Route::get('/asset/bin', 'App\Http\Controllers\AssetController@recycleBin')->name('assets.bin');
        Route::get('/asset/{asset}/restore', 'App\Http\Controllers\AssetController@restore')->name('assets.restore');
        Route::post('/asset/{asset}/remove', 'App\Http\Controllers\AssetController@forceDelete')->name('assets.remove');
        Route::post('/asset/{asset}/status', 'App\Http\Controllers\AssetController@changeStatus')->name('change.status');
    //Component Routes
        Route::resource('/components', 'App\Http\Controllers\ComponentController');
        Route::get('/component/bin', 'App\Http\Controllers\ComponentController@recycleBin')->name('components.bin');
        Route::get('/component/{component}/restore', 'App\Http\Controllers\ComponentController@restore')->name('components.restore');
        Route::post('/component/{component}/remove', 'App\Http\Controllers\ComponentController@forceDelete')->name('components.remove');
        Route::post('/components/pdf', 'App\Http\Controllers\ComponentController@downloadPDF')->name('components.pdf');
        Route::get('/components/{component}/pdf', 'App\Http\Controllers\ComponentController@downloadShowPDF')->name('components.showPdf');
        Route::post('components/{component}/comment/create', '\App\Http\Controllers\ComponentController@newComment')->name('component.comment');
    //Accessory Routes
        Route::resource('/accessories', 'App\Http\Controllers\AccessoryController');
        Route::get('/accessory/bin', 'App\Http\Controllers\AccessoryController@recycleBin')->name('accessories.bin');
        Route::get('/accessory/{accessory}/restore', 'App\Http\Controllers\AccessoryController@restore')->name('accessories.restore');
        Route::post('/accessory/{accessory}/remove', 'App\Http\Controllers\AccessoryController@forceDelete')->name('accessories.remove');
        Route::post('/accessory/pdf', 'App\Http\Controllers\AccessoryController@downloadPDF')->name('accessories.pdf');
        Route::get('/accessory/{accessory}/pdf', 'App\Http\Controllers\AccessoryController@downloadShowPDF')->name('accessories.showPdf');
        Route::post('accessory/{accessory}/comment/create', '\App\Http\Controllers\AccessoryController@newComment')->name('accessories.comment');
    //Consumable Routes
        Route::resource('/consumables', 'App\Http\Controllers\ConsumableController');
        Route::get('/consumable/bin', 'App\Http\Controllers\ConsumableController@recycleBin')->name('consumables.bin');
        Route::get('/consumable/{consumable}/restore', 'App\Http\Controllers\ConsumableController@restore')->name('consumables.restore');
        Route::post('/consumable/{consumable}/remove', 'App\Http\Controllers\ConsumableController@forceDelete')->name('consumables.remove');
        Route::post('/consumable/pdf', 'App\Http\Controllers\ConsumableController@downloadPDF')->name('consumables.pdf');
        Route::get('/consumable/{consumable}/pdf', 'App\Http\Controllers\ConsumableController@downloadShowPDF')->name('consumables.showPdf');
        Route::post('consumables/comment/create', 'App\Http\Controllers\ConsumableController@newComment')->name('consumables.comment');
        Route::post('/consumable/{consumable}/status', 'App\Http\Controllers\ConsumableController@changeStatus')->name('consumables.status');



        Route::resource('/status', 'App\Http\Controllers\StatusController');





        Route::post('assets/comment/create',[\App\Http\Controllers\AssetController::class, "newComment"] )->name('asset.comment');

        Route::get('/{type}/{id}/{method}/403/', 'App\Http\Controllers\ErrorController@forbidden')->name('errors.forbidden');

        Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');
//      exports
        Route::post("/exportassets", [\App\Http\Controllers\AssetController::class, "export"]);
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
        Route::get('chart/asset/values', 'App\Http\Controllers\ChartController@getAssetValueChart');
        Route::get('chart/asset/audits', 'App\Http\Controllers\ChartController@getAssetAuditChart');

});
