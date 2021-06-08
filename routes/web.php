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

    //manufactures routes
    Route::get("manufacturers", [\App\Http\Controllers\ManufacturerController::class, "show"]);
    Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "create"]);
    Route::get("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "list"]);
    Route::Post("manufacturers/create", [\App\Http\Controllers\ManufacturerController::class, "store"]);
    //

    //Administrator Permissions Middleware
    Route::group(['middleware'=>'role:1'], function(){
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::resource('/photo', 'App\Http\Controllers\PhotoController');

        Route::post('photo/upload', 'App\Http\Controllers\PhotoController@upload');
    });
});
