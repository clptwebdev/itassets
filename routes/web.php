<?php

use Illuminate\Support\Facades\Artisan;
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
require __DIR__ . '/auth.php';

Route::group(['middleware' => 'auth'], function() {
    Route::get('/', function() {
        if(auth()->user()->role_id == 1)
        {
            $locations = \App\Models\Location::all();
            $assets = \App\Models\Asset::all();
        } else
        {
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

    Route::get('/dashboard', function() {
        if(auth()->user()->role_id == 1)
        {
            $locations = \App\Models\Location::all();
            $assets = \App\Models\Asset::all();
        } else
        {
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

    //Super Admmin

    //Super Admin or Admin
    Route::group(['middleware' => 'admin.role'], function() {
        Route::resource('/users', 'App\Http\Controllers\UserController');
        Route::get('/user/permissions', 'App\Http\Controllers\UserController@userPermissions')->name('user.permissions');
        Route::get('/users/{id}/role/{role}', 'App\Http\Controllers\UserController@changePermission')->name('change.permission');
        Route::get('/users/{id}/locations', 'App\Http\Controllers\UserController@getLocations')->name('user.permission');
    });

    //User Manager

    //User
        Route::get('/user/details', 'App\Http\Controllers\UserController@userDetails')->name('user.details');
        Route::get('/user/password', 'App\Http\Controllers\UserController@userPassword')->name('user.password');
        Route::post('/user/details/update', 'App\Http\Controllers\UserController@updateDetails')->name('user.update');
        Route::post('/user/details/update', 'App\Http\Controllers\UserController@updateDetails')->name('user.update');
        Route::get('/user/forgotpassword', 'App\Http\Controllers\UserController@forgotPassword')->name('forgot.my.password');
        Route::post('/user/forgotpasswordstore', 'App\Http\Controllers\UserController@storePass')->name('forgot.my.password.store');
        Route::post('/user/change/password', 'App\Http\Controllers\UserController@changePassword')->name('change.password.store');
        Route::post('/users/pdf', 'App\Http\Controllers\UserController@downloadPDF')->name('users.pdf');
        Route::get('/user/{user}/pdf', 'App\Http\Controllers\UserController@downloadShowPDF')->name('user.showPdf');
    //Administrator Permissions Middleware
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::resource('/comment', 'App\Http\Controllers\CommentController');
        Route::post('permissions/users', 'App\Http\Controllers\UserController@permissions');
        Route::resource('/photo', 'App\Http\Controllers\PhotoController');
        Route::resource('/depreciation', 'App\Http\Controllers\DepreciationController');
        Route::resource('/fieldsets', 'App\Http\Controllers\FieldsetController');
        Route::resource('/fields', 'App\Http\Controllers\FieldController');
        Route::post('photo/upload', 'App\Http\Controllers\PhotoController@upload');
    //Archives
        Route::resource('archives', App\Http\Controllers\ArchiveController::class)->only(['index', 'show', 'destroy']);
        Route::get('/asset/archives', 'App\Http\Controllers\ArchiveController@assets')->name('archives.assets');
        Route::get('/accessory/archives', 'App\Http\Controllers\ArchiveController@accessories')->name('archives.accessories');
        Route::post('/archive/pdf', 'App\Http\Controllers\Archiveontroller@downloadPDF')->name('archives.pdf');
        Route::get('/archive/{archive}/pdf', 'App\Http\Controllers\ArchiveController@downloadShowPDF')->name('archives.showPdf');
    //Asset Model Routes
        Route::resource('/asset-models', 'App\Http\Controllers\AssetModelController');
        Route::get('/asset-model/pdf', 'App\Http\Controllers\AssetModelController@downloadPDF')->name('asset-model.pdf');
        Route::get('/asset-model/{assetModel}/pdf', 'App\Http\Controllers\AssetModelController@downloadShowPDF')->name('asset-model.showPdf');
        
    // Asset Routes
        Route::resource('/assets', 'App\Http\Controllers\AssetController');
        Route::post('/assets/search',[\App\Http\Controllers\AssetController::class, "search"] )->name('assets.search');
        Route::post('/assets/filter', 'App\Http\Controllers\AssetController@filter')->name('assets.filter');
        Route::get('/status/{status}/assets', 'App\Http\Controllers\AssetController@status')->name('assets.status');
        Route::get('/location/{location}/assets', 'App\Http\Controllers\AssetController@location')->name('assets.location');
        Route::post('/assets/pdf', 'App\Http\Controllers\AssetController@downloadPDF')->name('assets.pdf');
        Route::get('/asset/{asset}/pdf', 'App\Http\Controllers\AssetController@downloadShowPDF')->name('asset.showPdf');
        Route::get('/asset/bin', 'App\Http\Controllers\AssetController@recycleBin')->name('assets.bin');
        Route::get('/asset/{asset}/restore', 'App\Http\Controllers\AssetController@restore')->name('assets.restore');
        Route::post('/asset/{asset}/remove', 'App\Http\Controllers\AssetController@forceDelete')->name('assets.remove');
        Route::post('/asset/{asset}/status', 'App\Http\Controllers\AssetController@changeStatus')->name('change.status');
        Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');
        Route::post('assets/comment/create',[\App\Http\Controllers\AssetController::class, "newComment"] )->name('asset.comment');
    //Comment Routes
        Route::resource('/comment', 'App\Http\Controllers\CommentController');
    //Component Routes
        Route::resource('/components', 'App\Http\Controllers\ComponentController');
        Route::get('/component/bin', 'App\Http\Controllers\ComponentController@recycleBin')->name('components.bin');
        Route::get('/component/{component}/restore', 'App\Http\Controllers\ComponentController@restore')->name('components.restore');
        Route::post('/component/{component}/remove', 'App\Http\Controllers\ComponentController@forceDelete')->name('components.remove');
        Route::post('/components/pdf', 'App\Http\Controllers\ComponentController@downloadPDF')->name('components.pdf');
        Route::get('/components/{component}/pdf', 'App\Http\Controllers\ComponentController@downloadShowPDF')->name('components.showPdf');
        Route::post('components/{component}/comment/create', '\App\Http\Controllers\ComponentController@newComment')->name('component.comment');
        Route::post('/component/{component}/status', 'App\Http\Controllers\ComponentController@changeStatus')->name('component.status');
    //Accessory Routes
        Route::resource('/accessories', 'App\Http\Controllers\AccessoryController');
        Route::get('/accessory/bin', 'App\Http\Controllers\AccessoryController@recycleBin')->name('accessories.bin');
        Route::get('/accessory/{accessory}/restore', 'App\Http\Controllers\AccessoryController@restore')->name('accessories.restore');
        Route::post('/accessory/{accessory}/remove', 'App\Http\Controllers\AccessoryController@forceDelete')->name('accessories.remove');
        Route::post('/accessory/pdf', 'App\Http\Controllers\AccessoryController@downloadPDF')->name('accessories.pdf');
        Route::get('/accessory/{accessory}/pdf', 'App\Http\Controllers\AccessoryController@downloadShowPDF')->name('accessories.showPdf');
        Route::post('accessory/{accessory}/comment/create', '\App\Http\Controllers\AccessoryController@newComment')->name('accessories.comment');
        Route::post('/accessory/{accessory}/status', 'App\Http\Controllers\AccessoryController@changeStatus')->name('accessories.status');
    //Consumable Routes
        Route::resource('/consumables', 'App\Http\Controllers\ConsumableController');
        Route::get('/consumable/bin', 'App\Http\Controllers\ConsumableController@recycleBin')->name('consumables.bin');
        Route::get('/consumable/{consumable}/restore', 'App\Http\Controllers\ConsumableController@restore')->name('consumables.restore');
        Route::post('/consumable/{consumable}/remove', 'App\Http\Controllers\ConsumableController@forceDelete')->name('consumables.remove');
        Route::post('/consumable/pdf', 'App\Http\Controllers\ConsumableController@downloadPDF')->name('consumables.pdf');
        Route::get('/consumable/{consumable}/pdf', 'App\Http\Controllers\ConsumableController@downloadShowPDF')->name('consumables.showPdf');
        Route::post('consumables/comment/create', 'App\Http\Controllers\ConsumableController@newComment')->name('consumables.comment');
        Route::post('/consumable/{consumable}/status', 'App\Http\Controllers\ConsumableController@changeStatus')->name('consumables.status');
    //Category Routes
        Route::resource('/category', 'App\Http\Controllers\CategoryController');
        Route::post('/category/pdf', 'App\Http\Controllers\CategoryController@downloadPDF')->name('category.pdf');
        Route::get('/category/{category}/pdf', 'App\Http\Controllers\CategoryController@downloadShowPDF')->name('category.showPdf');
    //LocationControllers
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::get('/locations/pdf', 'App\Http\Controllers\LocationController@downloadPDF')->name('location.pdf');
        Route::get('/locations/{location}/pdf', 'App\Http\Controllers\LocationController@downloadShowPDF')->name('location.showPdf');
        Route::get("/exportlocations", [\App\Http\Controllers\LocationController::class, "export"]);
    //Manufacturer Routes
        Route::resource('/manufacturers', \App\Http\Controllers\ManufacturerController::class);
        Route::get('/manufactuer/pdf', 'App\Http\Controllers\ManufacturerController@downloadPDF')->name('manufacturer.pdf');
        Route::get('/manufacturer/{manufacturer}/pdf', 'App\Http\Controllers\ManufacturerController@downloadShowPDF')->name('manufacturer.showPdf');
        Route::get("/exportmanufacturers", [\App\Http\Controllers\ManufacturerController::class, "export"]);
    //Permission Routes

    //Request
        Route::post('/request/transfer', 'App\Http\Controllers\RequestsController@transfer')->name('request.transfer');
        Route::post('/request/dispose', 'App\Http\Controllers\RequestsController@disposal')->name('request.disposal');
        Route::get('/request/{requests}/handle/{status}', 'App\Http\Controllers\RequestsController@handle')->name('request.handle');
        Route::get('/requests', 'App\Http\Controllers\RequestsController@index')->name('requests.index');
    //Reports
        Route::get('/reports', '\App\Http\Controllers\ReportController@index')->name('reports.index');
    //Supplier
        Route::resource('/suppliers', 'App\Http\Controllers\SupplierController');
        Route::get('/supplier/pdf', 'App\Http\Controllers\SupplierController@downloadPDF')->name('suppliers.pdf');
        Route::get('/supplier/{supplier}/pdf', 'App\Http\Controllers\SupplierController@downloadShowPDF')->name('suppliers.showPdf');
        Route::get("/exportsuppliers", [\App\Http\Controllers\SupplierController::class, "export"]);
    //Transfers
        Route::get('/transfers', 'App\Http\Controllers\TransferController@index')->name('transfers.index');
        Route::get('/asset/transfers', 'App\Http\Controllers\TransferController@assets')->name('transfers.assets');
        Route::get('/accessory/transfers', 'App\Http\Controllers\TransferController@accessories')->name('transfers.accessories');
    //Database Backups Routes (Doesn't include import routes)
        Route::resource('/databasebackups', \App\Http\Controllers\BackupController::class);
        Route::get('/databasebackups/create/dbbackup', [\App\Http\Controllers\BackupController::class, "createDB"])->name('backupdb.create');
        Route::get('/databasebackups/create/backup', [\App\Http\Controllers\BackupController::class, "createFull"])->name('backup.create');
        Route::get('/databasebackups/clean/backups', [\App\Http\Controllers\BackupController::class, "dbClean"])->name('backup.clean');
        Route::get('/databasebackupdownload/{$file_name}', [\App\Http\Controllers\BackupController::class , "download"])->name('download.backup');

// Manufacturers Routes (Doesn't include import routes)


// status Routes (Doesn't include import routes)
    Route::resource('/status', 'App\Http\Controllers\StatusController');

//asset Models
    Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');

//Miscellaneous
    Route::resource('/miscellaneous', "\App\Http\Controllers\MiscellaneaController");
    Route::post('/miscellaneous/{miscellanea}', "\App\Http\Controllers\MiscellaneaController@changeStatus")->name('miscellaneous.status');
    Route::post('/miscellaneous/comment/create', '\App\Http\Controllers\MiscellaneaController@newComment')->name('miscellaneous.comment');
    Route::get('/miscellanea/bin', 'App\Http\Controllers\MiscellaneaController@recycleBin')->name('miscellaneous.bin');
    Route::get('/miscellaneous/{miscellanea}/restore', 'App\Http\Controllers\MiscellaneaController@restore')->name('miscellaneous.restore');
    Route::post('/miscellaneous/{miscellanea}/remove', 'App\Http\Controllers\MiscellaneaController@forceDelete')->name('miscellaneous.remove');
    Route::post('/miscellanea/pdf', 'App\Http\Controllers\MiscellaneaController@downloadPDF')->name('miscellaneous.pdf');
    Route::get('/miscellanea/{miscellanea}/pdf', 'App\Http\Controllers\MiscellaneaController@downloadShowPDF')->name('miscellaneous.showPdf');


//exports
    Route::post("/exportassets", [\App\Http\Controllers\AssetController::class, "export"]);
    Route::get("/exportmiscellaneous", [\App\Http\Controllers\MiscellaneaController::class, "export"]);
    Route::get("/exportconsumables", [\App\Http\Controllers\ConsumableController::class, "export"]);
    Route::get("/exportlocations", [\App\Http\Controllers\LocationController::class, "export"]);
    Route::get("/exportsuppliers", [\App\Http\Controllers\SupplierController::class, "export"]);
    Route::get("/exportusers", [\App\Http\Controllers\UserController::class, "export"]);
    Route::get("/exportcomponents", [\App\Http\Controllers\ComponentController::class, "export"]);
    Route::get("/exportaccessories", [\App\Http\Controllers\AccessoryController::class, "export"]);
    Route::Post("/exportlogs", [\App\Http\Controllers\LogController::class, "export"]);
//Imports
    Route::Post("manufacturers/create/ajax", [\App\Http\Controllers\ManufacturerController::class, "ajaxMany"]);
    Route::post("/importassets", [\App\Http\Controllers\AssetController::class, "import"]);
    Route::post("/importmanufacturer", [\App\Http\Controllers\ManufacturerController::class, "import"]);
    Route::post("/importcomponents", [\App\Http\Controllers\ComponentController::class, "import"]);
    Route::post("/importacessories", [\App\Http\Controllers\AccessoryController::class, "import"]);
    Route::post("/importconsumables", [\App\Http\Controllers\ConsumableController::class, "import"]);
    Route::post("/importmiscellaneous", [\App\Http\Controllers\MiscellaneaController::class, "import"]);
    Route::Post("components/create/ajax", [\App\Http\Controllers\ComponentController::class, "ajaxMany"]);
    Route::Post("accessories/create/ajax", [\App\Http\Controllers\AccessoryController::class, "ajaxMany"]);
    Route::Post("consumables/create/ajax", [\App\Http\Controllers\ConsumableController::class, "ajaxMany"]);
    Route::Post("assets/create/ajax", [\App\Http\Controllers\AssetController::class, "ajaxMany"]);
    Route::Post("miscellaneous/create/ajax", [\App\Http\Controllers\MiscellaneaController::class, "ajaxMany"]);
    Route::Post("components/export-import-errors", [\App\Http\Controllers\ComponentController::class, "importErrors"])->name("componentexport.import");
    Route::Post("accessories/export-import-errors", [\App\Http\Controllers\AccessoryController::class, "importErrors"])->name("accessoryexport.import");
    Route::Post("consumables/export-import-errors", [\App\Http\Controllers\ConsumableController::class, "importErrors"])->name("consumableexport.import");
    Route::Post("assets/export-import-errors", [\App\Http\Controllers\AssetController::class, "importErrors"])->name("export.import");
    Route::Post("miscellaneous/export-import-errors", [\App\Http\Controllers\MiscellaneaController::class, "importErrors"])->name("miscellaneaexport.import");

//Javascript pie charts for dashboard
    Route::get('chart/pie/locations', 'App\Http\Controllers\ChartController@getPieChart');
    Route::get('chart/asset/values', 'App\Http\Controllers\ChartController@getAssetValueChart');
    Route::get('chart/asset/audits', 'App\Http\Controllers\ChartController@getAssetAuditChart');
//Logs View
    Route::get("/logs", [\App\Http\Controllers\LogController::class, "index"])->name("logs.index");
//documentation link
    Route::get("/help/documentation" , function(){ return view('documentation.Documents');})->name('documentation.index');
    Route::get("/help/documentation/{section}" , function(){ return view('documentation.Documents');})->name('documentation.index.section');
});
//403 redirects
Route::get('/{type}/{id}/{method}/403/', 'App\Http\Controllers\ErrorController@forbidden')->name('errors.forbidden');


