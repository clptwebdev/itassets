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
        /* Route::resource('admin/groups', 'App\Http\Controllers\RoleController');
        Route::delete('admin/users/delete/multi', 'App\Http\Controllers\UserController@destroyMulti')->name('users.deleteMulti');
        //Settings */
    });
});
