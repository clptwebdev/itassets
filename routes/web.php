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
        return view('dashboard');
    })->name('home');

    Route::get('/dashboard', function(){
        return view('dashboard');
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

        Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');
        Route::get("/export", [\App\Http\Controllers\AssetController::class, "export"]);

        Route::get("manufacturers", [\App\Http\Controllers\ManufacturerController::class, "show"]);
        Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "create"]);
        Route::get("manufacturers/edit/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "edit"]);
        Route::Put("manufacturers/edit/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "update"]);
        Route::delete("manufacturers/delete/{manufacturers}", [\App\Http\Controllers\ManufacturerController::class, "destroy"]);
        Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "list"]);
        Route::Post("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "store"]);
        //
    });
});
