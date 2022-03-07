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

    Route::get('/dashboard', [\App\Http\Controllers\HomeController::class, "index"])->name('dashboard');
    Route::get('/', [\App\Http\Controllers\HomeController::class, "index"])->name('home');

    //Super Admin or Admin
    Route::group(['middleware' => 'admin.role'], function() {
        Route::resource('/users', 'App\Http\Controllers\UserController');
        Route::get('/user/permissions', 'App\Http\Controllers\UserController@userPermissions')->name('user.permissions');
        Route::get('/users/{id}/role/{role}', 'App\Http\Controllers\UserController@changePermission')->name('change.permission');
        Route::get('/users/{id}/locations', 'App\Http\Controllers\UserController@getLocations')->name('user.permission');
    });

    //Dashboard
    Route::get('/statistics', 'App\Http\Controllers\HomeController@statistics')->name('dashboard.statistics');

    //User Manager

    //User
    Route::get('/user/details', 'App\Http\Controllers\UserController@userDetails')->name('user.details');
    Route::get('/user/password', 'App\Http\Controllers\UserController@userPassword')->name('user.password');
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
    Route::post('/archive/pdf', 'App\Http\Controllers\ArchiveController@downloadPDF')->name('archives.pdf');
    Route::get('/archive/{archive}/pdf', 'App\Http\Controllers\ArchiveController@downloadShowPDF')->name('archives.showPdf');
    Route::get('/archive/{archive}/restore', 'App\Http\Controllers\ArchiveController@restoreArchive')->name('archives.restore');
    //Asset Model Routes
    Route::resource('/asset-models', 'App\Http\Controllers\AssetModelController');
    Route::get('/asset-model/pdf', 'App\Http\Controllers\AssetModelController@downloadPDF')->name('asset-model.pdf');
    Route::get('/asset-model/{assetModel}/pdf', 'App\Http\Controllers\AssetModelController@downloadShowPDF')->name('asset-model.showPdf');
    Route::post('/search/models/', 'App\Http\Controllers\AssetModelController@search')->name('model.search');
    Route::post('/model/preview/', 'App\Http\Controllers\AssetModelController@preview')->name('model.preview');
    Route::post('/model/create/', 'App\Http\Controllers\AssetModelController@ajaxCreate')->name('model.create');

    // Asset Routes
    Route::resource('/assets', 'App\Http\Controllers\AssetController');
    Route::post('/assets/search', [\App\Http\Controllers\AssetController::class, "search"])->name('assets.search');
    Route::post('/asset/filter', 'App\Http\Controllers\AssetController@filter')->name('asset.filter');
    Route::get('/asset/filter/clear', 'App\Http\Controllers\AssetController@clearFilter')->name('asset.clear.filter');
    Route::get('/asset/filter', 'App\Http\Controllers\AssetController@filter')->name('asset.filtered');
    Route::get('/status/{status}/assets', 'App\Http\Controllers\AssetController@status')->name('assets.status');
    Route::get('/location/{location}/assets', 'App\Http\Controllers\AssetController@location')->name('assets.location');
    Route::post('/assets/pdf', 'App\Http\Controllers\AssetController@downloadPDF')->name('assets.pdf');
    Route::get('/asset/{asset}/pdf', 'App\Http\Controllers\AssetController@downloadShowPDF')->name('asset.showPdf');
    Route::get('/asset/bin', 'App\Http\Controllers\AssetController@recycleBin')->name('assets.bin');
    Route::get('/asset/{asset}/restore', 'App\Http\Controllers\AssetController@restore')->name('assets.restore');
    Route::post('/asset/{asset}/remove', 'App\Http\Controllers\AssetController@forceDelete')->name('assets.remove');
    Route::post('/asset/{asset}/status', 'App\Http\Controllers\AssetController@changeStatus')->name('change.status');
    Route::get('assets/{model}/model', 'App\Http\Controllers\AssetController@model')->name('asset.model');
    Route::post('assets/comment/create', [\App\Http\Controllers\AssetController::class, "newComment"])->name('asset.comment');
    Route::post('assets/disposal', 'App\Http\Controllers\AssetController@bulkDisposal')->name('assets.bulk.disposal');
    Route::Post("assets/export-disposal-errors", [\App\Http\Controllers\AssetController::class, "exportDisposeErrors"])->name("export.dispose.errors");
    Route::post('assets/transfer', 'App\Http\Controllers\AssetController@bulkTransfers')->name('assets.bulk.transfer');
    Route::Post("assets/export-transfer-errors", [\App\Http\Controllers\AssetController::class, "exportTransferErrors"])->name("export.transfer.errors");
    //Assets Under Construction
    Route::resource("/aucs", \App\Http\Controllers\AUCController::class);
    Route::post('/auc/filter', 'App\Http\Controllers\AUCController@filter')->name('auc.filter');
    Route::get('/auc/filter/clear', 'App\Http\Controllers\AUCController@clearFilter')->name('auc.clear.filter');
    Route::get('/auc/filter', 'App\Http\Controllers\AUCController@filter')->name('auc.filtered');
    Route::get('/auc/bin', 'App\Http\Controllers\AUCController@recycleBin')->name('auc.bin');
    Route::get('/auc/{auc}/restore', 'App\Http\Controllers\AUCController@restore')->name('auc.restore');
    Route::post('/auc/{auc}/remove', 'App\Http\Controllers\AUCController@forceDelete')->name('auc.remove');
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
    Route::post('/component/filter', 'App\Http\Controllers\ComponentController@filter')->name('component.filter');
    Route::get('/component/filter/clear', 'App\Http\Controllers\ComponentController@clearFilter')->name('component.clear.filter');
    Route::get('/component/filter', 'App\Http\Controllers\ComponentController@filter')->name('component.filtered');
    //Accessory Routes
    Route::resource('/accessories', 'App\Http\Controllers\AccessoryController');
    Route::post('/accessory/filter', 'App\Http\Controllers\AccessoryController@filter')->name('accessory.filter');
    Route::get('/accessory/filter/clear', 'App\Http\Controllers\AccessoryController@clearFilter')->name('accessory.clear.filter');
    Route::get('/accessory/filter', 'App\Http\Controllers\AccessoryController@filter')->name('accessory.filtered');
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
    Route::post('/search/category/', 'App\Http\Controllers\CategoryController@search')->name('category.search');
    //LocationControllers
    Route::resource('/location', 'App\Http\Controllers\LocationController');
    Route::get('/locations/pdf', 'App\Http\Controllers\LocationController@downloadPDF')->name('location.pdf');
    Route::get('/locations/{location}/pdf', 'App\Http\Controllers\LocationController@downloadShowPDF')->name('location.showPdf');
    Route::get("/exportlocations", [\App\Http\Controllers\LocationController::class, "export"]);
    Route::post('/search/locations/', 'App\Http\Controllers\LocationController@search')->name('location.search');
    Route::post('/location/preview/', 'App\Http\Controllers\LocationController@preview')->name('location.preview');
    //Manufacturer Routes
    Route::resource('/manufacturers', \App\Http\Controllers\ManufacturerController::class);
    Route::get('/manufactuer/pdf', 'App\Http\Controllers\ManufacturerController@downloadPDF')->name('manufacturer.pdf');
    Route::get('/manufacturer/{manufacturer}/pdf', 'App\Http\Controllers\ManufacturerController@downloadShowPDF')->name('manufacturer.showPdf');
    Route::get("/exportmanufacturers", [\App\Http\Controllers\ManufacturerController::class, "export"]);
    Route::Post("/manufacturer/filter", [\App\Http\Controllers\ManufacturerController::class, "filter"])->name("manufacturer.filter");
    Route::get('/manufacturer/filter', [\App\Http\Controllers\ManufacturerController::class, "filter"])->name('manufacturer.filtered');

    Route::get("/manufacturer/clear/filter", [\App\Http\Controllers\ManufacturerController::class, "clearFilter"])->name("manufacturer.clearfilter");
    //Permission Routes

    //Property
    Route::resource("/properties", \App\Http\Controllers\PropertyController::class);
    Route::post('/property/filter', 'App\Http\Controllers\PropertyController@filter')->name('property.filter');
    Route::get('/property/filter/clear', 'App\Http\Controllers\PropertyController@clearFilter')->name('property.clear.filter');
    Route::get('/property/filter', 'App\Http\Controllers\PropertyController@filter')->name('property.filtered');
    Route::get('/property/bin', 'App\Http\Controllers\PropertyController@recycleBin')->name('property.bin');
    Route::get('/property/{asset}/restore', 'App\Http\Controllers\PropertyController@restore')->name('property.restore');
    Route::post('/property/{asset}/remove', 'App\Http\Controllers\PropertyController@forceDelete')->name('property.remove');
    //Request
    Route::post('/request/transfer', 'App\Http\Controllers\RequestsController@transfer')->name('request.transfer');
    Route::post('/request/dispose', 'App\Http\Controllers\RequestsController@disposal')->name('request.disposal');
    Route::get('/request/{requests}/handle/{status}', 'App\Http\Controllers\RequestsController@handle')->name('request.handle');
    Route::post('/request/access', 'App\Http\Controllers\RequestsController@handleAccess')->name('request.access.handle');
    Route::get('/requests', 'App\Http\Controllers\RequestsController@index')->name('requests.index');
    Route::get('/requests/access', 'App\Http\Controllers\RequestsController@access')->name('requests.access');
    //Reports
    Route::get('/reports', '\App\Http\Controllers\ReportController@index')->name('reports.index');
    //Supplier
    Route::resource('/suppliers', 'App\Http\Controllers\SupplierController');
    Route::get('/supplier/pdf', 'App\Http\Controllers\SupplierController@downloadPDF')->name('suppliers.pdf');
    Route::get('/supplier/{supplier}/pdf', 'App\Http\Controllers\SupplierController@downloadShowPDF')->name('suppliers.showPdf');
    Route::get("/exportsuppliers", [\App\Http\Controllers\SupplierController::class, "export"]);
    Route::post('/search/suppliers/', 'App\Http\Controllers\SupplierController@search')->name('supplier.search');
    Route::post('/supplier/preview/', 'App\Http\Controllers\SupplierController@preview')->name('supplier.preview');
    //Transfers
    Route::get('/transfers', 'App\Http\Controllers\TransferController@index')->name('transfers.index');
    Route::get('/asset/transfers', 'App\Http\Controllers\TransferController@assets')->name('transfers.assets');
    Route::get('/accessory/transfers', 'App\Http\Controllers\TransferController@accessories')->name('transfers.accessories');
    //Database Backups Routes (Doesn't include import routes)
    Route::resource('/databasebackups', \App\Http\Controllers\BackupController::class);
    Route::get('/databasebackups/create/dbbackup', [\App\Http\Controllers\BackupController::class, "createDB"])->name('backupdb.create');
    Route::get('/databasebackups/create/backup', [\App\Http\Controllers\BackupController::class, "createFull"])->name('backup.create');
    Route::get('/databasebackups/clean/backups', [\App\Http\Controllers\BackupController::class, "dbClean"])->name('backup.clean');
    Route::get('/databasebackupdownload/{$file_name}', [\App\Http\Controllers\BackupController::class, "download"])->name('download.backup');

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
    Route::post('/miscellanea/filter', 'App\Http\Controllers\MiscellaneaController@filter')->name('miscellanea.filter');
    Route::get('/miscellanea/filter/clear', 'App\Http\Controllers\MiscellaneaController@clearFilter')->name('miscellanea.clear.filter');
    Route::get('/miscellanea/filter', 'App\Http\Controllers\MiscellaneaController@filter')->name('miscellanea.filtered');

//Caching
    Route::get('/cache/clear', 'App\Http\Controllers\HomeController@clearCache')->name('cache.clear');

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
//settings page
    Route::get("/settings", [\App\Http\Controllers\SettingsController::class, "index"])->name("settings.view");
    Route::Post("/settings/accessories/export", [\App\Http\Controllers\SettingsController::class, "accessories"])->name("settings.accessories");
    Route::Post("/settings/assets/export", [\App\Http\Controllers\SettingsController::class, "assets"])->name("settings.assets");
    Route::Post("/settings/components/export", [\App\Http\Controllers\SettingsController::class, "components"])->name("settings.components");
    Route::Post("/settings/miscellaneous/export", [\App\Http\Controllers\SettingsController::class, "miscellaneous"])->name("settings.miscellaneous");
    Route::get("/settings/roles/create", [\App\Http\Controllers\SettingsController::class, "roleBoot"])->name('role.boot');

//Javascript pie charts for dashboard
    Route::get('chart/pie/locations', 'App\Http\Controllers\ChartController@getPieChart');
    Route::get('chart/asset/values', 'App\Http\Controllers\ChartController@getAssetValueChart');
    Route::get('chart/asset/audits', 'App\Http\Controllers\ChartController@getAssetAuditChart');
//Logs View
    Route::get("/logs", [\App\Http\Controllers\LogController::class, "index"])->name("logs.index");
    Route::get("/logs/delete", [\App\Http\Controllers\LogController::class, "destroy"])->name("logs.destroy");
    Route::get('/logs/filter/clear', [\App\Http\Controllers\LogController::class, "clearFilter"])->name('logs.clear.filter');
    Route::post('/logs/filter', [\App\Http\Controllers\LogController::class, "filter"])->name('logs.filter');
    Route::get('/logs/filter', [\App\Http\Controllers\LogController::class, "filter"])->name('logs.filtered');

//documentation link
    Route::get("/help/documentation", function() {
        return view('documentation.Documents');
    })->name('documentation.index');
    Route::get("/help/documentation/{section}", function() {
        return view('documentation.Documents');
    })->name('documentation.index.section');

    //roles
    //roles
    Route::Post('/role/create', [\App\Http\Controllers\RoleController::class, "store"])->name('role.create');
    Route::Post('/role/sync', [\App\Http\Controllers\RoleController::class, "roleSync"])->name('role.sync');
    Route::Post('/role/delete/', [\App\Http\Controllers\RoleController::class, "destroy"])->name('role.destroy');
    Route::Get('/role/default', [\App\Http\Controllers\RoleController::class, "default"])->name('role.default');
});



