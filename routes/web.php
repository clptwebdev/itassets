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

    //Super Admmin

    //Super Admin or Admin
    Route::group(['middleware'=>'admin.role'], function(){
        Route::resource('/users', 'App\Http\Controllers\UserController');
    });

    //User Manager

    //User


    //Administrator Permissions Middleware
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::resource('/category', 'App\Http\Controllers\CategoryController');
        Route::post('permissions/users', 'App\Http\Controllers\UserController@permissions');
        Route::resource('/supplier', 'App\Http\Controllers\SupplierController');
        Route::resource('/photo', 'App\Http\Controllers\PhotoController');
        Route::resource('/asset-models', 'App\Http\Controllers\AssetModelController');
        Route::resource('/depreciation', 'App\Http\Controllers\DepreciationController');
        Route::resource('/fieldsets', 'App\Http\Controllers\FieldsetController');
        Route::resource('/fields', 'App\Http\Controllers\FieldController');
        Route::post('photo/upload', 'App\Http\Controllers\PhotoController@upload');
        
        Route::resource('/assets', 'App\Http\Controllers\AssetController');
        Route::post('/assets/filter', 'App\Http\Controllers\AssetController@filter')->name('assets.filter');
        Route::get('/status/{status}/assets', 'App\Http\Controllers\AssetController@status')->name('assets.status');
        Route::get('/location/{location}/assets', 'App\Http\Controllers\AssetController@location')->name('assets.location');
        Route::get('/assets/bin', 'App\Http\Controllers\AssetController@bin')->name('assets.bin');
        Route::post('/assets/pdf', 'App\Http\Controllers\AssetController@downloadPDF')->name('assets.pdf');
        Route::get('/asset/{asset}/pdf', 'App\Http\Controllers\AssetController@downloadShowPDF')->name('asset.showPdf');
        Route::get('/asset/bin', 'App\Http\Controllers\AssetController@recycleBin')->name('assets.bin');
        Route::get('/asset/{asset}/restore', 'App\Http\Controllers\AssetController@restore')->name('assets.restore');
        Route::post('/asset/{asset}remove', 'App\Http\Controllers\AssetController@forceDelete')->name('assets.remove');



        Route::resource('/status', 'App\Http\Controllers\StatusController');
        Route::resource('/components', 'App\Http\Controllers\ComponentController');
        Route::get('/{type}/{id}/{method}/403/', 'App\Http\Controllers\ErrorController@forbidden')->name('errors.forbidden');




        Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');
//      exports
        Route::get("/exportassets", [\App\Http\Controllers\AssetController::class, "export"]);
        Route::get("/exportlocations", [\App\Http\Controllers\LocationController::class, "export"]);
        Route::get("/exportmanufacturers", [\App\Http\Controllers\ManufacturerController::class, "export"]);
        Route::get("/exportsuppliers", [\App\Http\Controllers\SupplierController::class, "export"]);
        Route::get("/exportusers", [\App\Http\Controllers\UserController::class, "export"]);
        Route::get("/exportcomponents", [\App\Http\Controllers\ComponentController::class, "export"]);
//
        Route::post("/importmanufacturer", [\App\Http\Controllers\ManufacturerController::class, "import"]);
        Route::post("/importcomponents", [\App\Http\Controllers\ComponentController::class, "import"]);
        Route::Post("components/create/import", [\App\Http\Controllers\ComponentController::class, "createMany"]);


        Route::get("manufacturers", [\App\Http\Controllers\ManufacturerController::class, "show"]);
        Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "create"]);
        Route::get("manufacturers/edit/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "edit"]);
        Route::Put("manufacturers/edit/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "update"]);
        Route::delete("manufacturers/delete/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "destroy"]);
        Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "list"]);
        Route::Post("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "store"]);
        Route::Post("manufacturers/create/import", [\App\Http\Controllers\ManufacturerController::class, "createMany"]);
        Route::Get("manufacturers/create/import", [\App\Http\Controllers\ManufacturerController::class, "createMany"]);
        Route::Post("manufacturers/create/ajax", [\App\Http\Controllers\ManufacturerController::class, "ajaxMany"]);

        //This needs fixing its trying to get variable array from import
        Route::Post("manufacturers/import-fail", [\App\Http\Controllers\ManufacturerController::class,"import"]);
        Route::get("manufacturers/import-fail", [\App\Http\Controllers\ManufacturerController::class,"import"]);

        Route::get('chart/pie/locations', 'App\Http\Controllers\ChartController@getPieChart');
        Route::get('chart/asset/values', 'App\Http\Controllers\ChartController@getAssetValueChart');
        Route::get('chart/asset/audits', 'App\Http\Controllers\ChartController@getAssetAuditChart');

});
