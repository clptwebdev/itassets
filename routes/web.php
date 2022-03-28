<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';

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
Route::controller(\App\Http\Controllers\OfficeLoginController::class)->group(function() {
    Route::get('login/microsoft', 'redirectToProvider');
    Route::get('login/microsoft/callback', 'handleProviderCallback');

});

Route::group(['middleware' => 'auth'], function() {

    Route::controller(\App\Http\Controllers\HomeController::class)->group(function() {
        //Dashboard
        Route::get('/dashboard', "index")->name('dashboard');
        Route::get('/business', "business")->name('business');
        Route::get('/', "index")->name('home');
        Route::get('/statistics', 'statistics')->name('dashboard.statistics');
        Route::get('/business/statistics', 'business_statistics')->name('business.statistics');
        //Caching
        Route::get('/cache/clear', 'clearCache')->name('cache.clear');
    });
    Route::controller(\App\Http\Controllers\UserController::class)->group(function() {
        //User
        Route::resource('/users', 'App\Http\Controllers\UserController');
        Route::get('/user/details', 'userDetails')->name('user.details');
        Route::get('/user/password', 'userPassword')->name('user.password');
        Route::post('/user/details/update', 'updateDetails')->name('user.update');
        Route::get('/user/forgotpassword', 'forgotPassword')->name('forgot.my.password');
        Route::post('/user/forgotpasswordstore', 'storePass')->name('forgot.my.password.store');
        Route::post('/user/change/password', 'changePassword')->name('change.password.store');
        Route::post('/users/pdf', 'downloadPDF')->name('users.pdf');
        Route::get('/user/{user}/pdf', 'downloadShowPDF')->name('user.showPdf');
        Route::get('/user/expired', 'invokeExpiredUsers')->name('user.expired');
        //Administrator Permissions Middleware
        Route::post('permissions/users', 'permissions');
        Route::get('/user/permissions', 'userPermissions')->name('user.permissions');
        Route::get('/users/{id}/role/{role}', 'changePermission')->name('change.permission');
        Route::get('/users/{id}/locations', 'getLocations')->name('user.permission');
        //export users
        Route::get("/exportusers", "export");
    });
    Route::controller(\App\Http\Controllers\LocationController::class)->group(function() {
        //location
        Route::resource('/location', 'App\Http\Controllers\LocationController');
        Route::get('/locations/pdf', 'downloadPDF')->name('location.pdf');
        Route::get('/locations/{location}/pdf', 'downloadShowPDF')->name('location.showPdf');
        Route::post('/search/locations/', 'search')->name('location.search');
        Route::post('/location/preview/', 'preview')->name('location.preview');
        //exports
        Route::get("/exportlocations", "export");
    });
    Route::controller(\App\Http\Controllers\CommentController::class)->group(function() {
        //comments
        Route::resource('/comment', 'App\Http\Controllers\CommentController');
    });
    Route::controller(\App\Http\Controllers\PhotoController::class)->group(function() {
        //photos
        Route::resource('/photo', 'App\Http\Controllers\PhotoController');
        Route::post('/photo/upload', 'upload');
    });
    Route::controller(\App\Http\Controllers\DepreciationController::class)->group(function() {
        //photos
        Route::resource('/depreciation', 'App\Http\Controllers\DepreciationController');
    });
    Route::controller(\App\Http\Controllers\FieldsetController::class)->group(function() {
        //FieldSet
        Route::resource('/fieldsets', 'App\Http\Controllers\FieldsetController');
    });
    Route::controller(\App\Http\Controllers\FieldController::class)->group(function() {
        //Field
        Route::resource('/fields', 'App\Http\Controllers\FieldController');
    });
    Route::controller(\App\Http\Controllers\ArchiveController::class)->group(function() {
        //Archive
        Route::resource('archives', App\Http\Controllers\ArchiveController::class)->only(['index', 'show', 'destroy']);
        Route::get('/asset/archives', 'assets')->name('archives.assets');
        Route::get('/accessory/archives', 'accessories')->name('archives.accessories');
        Route::post('/archive/pdf', 'downloadPDF')->name('archives.pdf');
        Route::get('/archive/{archive}/pdf', 'downloadShowPDF')->name('archives.showPdf');
        Route::get('/archive/{archive}/restore', 'restoreArchive')->name('archives.restore');
    });
    Route::controller(\App\Http\Controllers\AssetModelController::class)->group(function() {
        //Asset Model Routes
        Route::resource('/asset-models', 'App\Http\Controllers\AssetModelController');
        Route::get('/asset-model/pdf', 'downloadPDF')->name('asset-model.pdf');
        Route::get('/asset-model/{assetModel}/pdf', 'downloadShowPDF')->name('asset-model.showPdf');
        Route::post('/search/models/', 'search')->name('model.search');
        Route::post('/model/preview/', 'preview')->name('model.preview');
        Route::post('/model/create/', 'ajaxCreate')->name('model.create');
    });
    Route::controller(\App\Http\Controllers\AssetController::class)->group(function() {
        // Asset Routes
        Route::resource('/assets', 'App\Http\Controllers\AssetController');
        Route::post('/assets/search', "search")->name('assets.search');
        Route::post('/asset/filter', 'filter')->name('asset.filter');
        Route::get('/asset/filter/clear', 'clearFilter')->name('asset.clear.filter');
        Route::get('/asset/filter', 'filter')->name('asset.filtered');
        Route::get('/status/{status}/assets', 'status')->name('assets.status');
        Route::get('/location/{location}/assets', 'location')->name('assets.location');
        Route::post('/assets/pdf', 'downloadPDF')->name('assets.pdf');
        Route::get('/asset/{asset}/pdf', 'downloadShowPDF')->name('asset.showPdf');
        Route::get('/asset/bin', 'recycleBin')->name('assets.bin');
        Route::get('/asset/{asset}/restore', 'restore')->name('assets.restore');
        Route::post('/asset/{asset}/remove', 'forceDelete')->name('assets.remove');
        Route::post('/asset/{asset}/status', 'changeStatus')->name('change.status');
        Route::get('assets/{model}/model', 'model')->name('asset.model');
        Route::post('assets/comment/create', "newComment")->name('asset.comment');
        Route::post('assets/disposal', 'bulkDisposal')->name('assets.bulk.disposal');
        Route::Post("assets/export-disposal-errors", "exportDisposeErrors")->name("export.dispose.errors");
        Route::post('assets/transfer', 'bulkTransfers')->name('assets.bulk.transfer');
        Route::Post("assets/export-transfer-errors", "exportTransferErrors")->name("export.transfer.errors");
        //Asset Models
        Route::get('assets/{model}/model', 'model')->name('asset.model');
        //Exports
        Route::post("/exportassets", "export");
        //Imports
        Route::post("/importassets", "import")->name('assets.import');
        Route::Post("assets/create/ajax", "ajaxMany");
        Route::Post("assets/export-import-errors", "importErrors")->name("export.import");
    });

    Route::controller(\App\Http\Controllers\AUCController::class)->group(function() {
        //Assets Under Construction
        Route::resource("/aucs", \App\Http\Controllers\AUCController::class);
        Route::post('/auc/filter', 'filter')->name('auc.filter');
        Route::get('/auc/filter/clear', 'clearFilter')->name('auc.clear.filter');
        Route::get('/auc/filter', 'filter')->name('auc.filtered');
        Route::get('/auc/bin', 'recycleBin')->name('auc.bin');
        Route::get('/auc/{auc}/restore', 'restore')->name('auc.restore');
        Route::post('/auc/{auc}/remove', 'forceDelete')->name('auc.remove');
        Route::get('/auc/{auc}/move', 'move')->name('auc.move');
        //Exports
        Route::post("/export/aucs", "export");
        //Imports
        Route::post("/import/aucs", "import");
        Route::Post("/import/aucs/errors", "importErrors");
        Route::Post("/import/aucs/errors/export", "exportImportErrors")->name("aucs.export.import");
        //PDF
        Route::post('/aucs/pdf', 'downloadPDF')->name('aucs.pdf');
        Route::get('/aucs/{auc}/pdf', 'downloadShowPDF')->name('aucs.showPdf');
    });
    Route::controller(\App\Http\Controllers\ComponentController::class)->group(function() {
        //Component Routes
        Route::resource('/components', 'App\Http\Controllers\ComponentController');
        Route::get('/component/bin', 'recycleBin')->name('components.bin');
        Route::get('/component/{component}/restore', 'restore')->name('components.restore');
        Route::post('/component/{component}/remove', 'forceDelete')->name('components.remove');
        Route::post('/components/pdf', 'downloadPDF')->name('components.pdf');
        Route::get('/components/{component}/pdf', 'downloadShowPDF')->name('components.showPdf');
        Route::post('components/{component}/comment/create', 'newComment')->name('component.comment');
        Route::post('/component/{component}/status', 'changeStatus')->name('component.status');
        Route::post('/component/filter', 'filter')->name('component.filter');
        Route::get('/component/filter/clear', 'clearFilter')->name('component.clear.filter');
        Route::get('/component/filter', 'filter')->name('component.filtered');
        //Exports
        Route::Post("components/export-import-errors", "importErrors")->name("componentexport.import");
        Route::get("/exportcomponents", "export");
        //Imports
        Route::post("/importcomponents", "import");
        Route::Post("components/create/ajax", "ajaxMany");
    });
    Route::controller(\App\Http\Controllers\AccessoryController::class)->group(function() {
        //Accessory Routes
        Route::resource('/accessories', 'App\Http\Controllers\AccessoryController');
        Route::post('/accessory/filter', 'filter')->name('accessory.filter');
        Route::get('/accessory/filter/clear', 'clearFilter')->name('accessory.clear.filter');
        Route::get('/accessory/filter', 'filter')->name('accessory.filtered');
        Route::get('/accessory/bin', 'recycleBin')->name('accessories.bin');
        Route::get('/accessory/{accessory}/restore', 'restore')->name('accessories.restore');
        Route::post('/accessory/{accessory}/remove', 'forceDelete')->name('accessories.remove');
        Route::post('/accessory/pdf', 'downloadPDF')->name('accessories.pdf');
        Route::get('/accessory/{accessory}/pdf', 'downloadShowPDF')->name('accessories.showPdf');
        Route::post('accessory/{accessory}/comment/create', 'newComment')->name('accessories.comment');
        Route::post('/accessory/{accessory}/status', 'changeStatus')->name('accessories.status');
        //Exports
        Route::get("/exportaccessories", "export");
        //Imports
        Route::post("/importacessories", "import");
        Route::Post("accessories/create/ajax", "ajaxMany");
        Route::Post("accessories/export-import-errors", "importErrors")->name("accessoryexport.import");
    });
    Route::controller(\App\Http\Controllers\ConsumableController::class)->group(function() {
        //Consumable Routes
        Route::resource('/consumables', 'App\Http\Controllers\ConsumableController');
        Route::get('/consumable/bin', 'recycleBin')->name('consumables.bin');
        Route::get('/consumable/{consumable}/restore', 'restore')->name('consumables.restore');
        Route::post('/consumable/{consumable}/remove', 'forceDelete')->name('consumables.remove');
        Route::post('/consumable/pdf', 'downloadPDF')->name('consumables.pdf');
        Route::get('/consumable/{consumable}/pdf', 'downloadShowPDF')->name('consumables.showPdf');
        Route::post('consumables/comment/create', 'newComment')->name('consumables.comment');
        Route::post('/consumable/{consumable}/status', 'changeStatus')->name('consumables.status');
        //Exports
        Route::get("/exportconsumables", "export");
        //Imports
        Route::post("/importconsumables", "import");
        Route::Post("consumables/create/ajax", "ajaxMany");
        Route::Post("consumables/export-import-errors", "importErrors")->name("consumableexport.import");

    });
    Route::controller(\App\Http\Controllers\CategoryController::class)->group(function() {
        //Category Routes
        Route::resource('/category', 'App\Http\Controllers\CategoryController');
        Route::post('/category/pdf', 'downloadPDF')->name('category.pdf');
        Route::get('/category/{category}/pdf', 'downloadShowPDF')->name('category.showPdf');
        Route::post('/search/category/', 'search')->name('category.search');

    });
    Route::controller(\App\Http\Controllers\FFEController::class)->group(function() {
        //FFE
        Route::resource("/ffes", \App\Http\Controllers\FFEController::class);
        Route::post('/ffe/filter', 'filter')->name('ffe.filter');
        Route::get('/ffe/filter/clear', 'clearFilter')->name('ffe.clear.filter');
        Route::get('/ffe/filter', 'filter')->name('ffe.filtered');
        Route::get('/ffe/bin', 'recycleBin')->name('ffe.bin');
        Route::get('/ffe/{ffe}/restore', 'restore')->name('ffe.restore');
        Route::post('/ffe/{ffe}/remove', 'forceDelete')->name('ffe.remove');
    });
    Route::controller(\App\Http\Controllers\ManufacturerController::class)->group(function() {
        //Manufacturer Routes
        Route::resource('/manufacturers', \App\Http\Controllers\ManufacturerController::class);
        Route::get('/manufactuer/pdf', 'downloadPDF')->name('manufacturer.pdf');
        Route::get('/manufacturer/{manufacturer}/pdf', 'downloadShowPDF')->name('manufacturer.showPdf');
        Route::get("/exportmanufacturers", "export");
        Route::Post("/manufacturer/filter", "filter")->name("manufacturer.filter");
        Route::get('/manufacturer/filter', "filter")->name('manufacturer.filtered');
        Route::get("/manufacturer/clear/filter", "clearFilter")->name("manufacturer.clearfilter");
        //Imports
        Route::Post("manufacturers/create/ajax", "ajaxMany");
        Route::post("/importmanufacturer", "import");
    });

    /////////////////////////////////////////////
    /////////////// Property Routes /////////////
    /////////////////////////////////////////////

    Route::controller(\App\Http\Controllers\PropertyController::class)->group(function() {
        //Property
        Route::resource("/properties", \App\Http\Controllers\PropertyController::class);
        Route::post('/property/filter', 'filter')->name('property.filter');
        Route::get('/property/filter/clear', 'clearFilter')->name('property.clear.filter');
        Route::get('/property/filter', 'filter')->name('property.filtered');
        Route::get('/property/bin', 'recycleBin')->name('property.bin');
        Route::get('/property/{asset}/restore', 'restore')->name('property.restore');
        Route::post('/property/{asset}/remove', 'forceDelete')->name('property.remove');
        Route::post('/property/{asset}/comment', 'newComment')->name('property.comment');
        //Exports
        Route::post("/export/properties", "export");
        //Imports
        Route::post("/import/properties", "import");
        Route::Post("/import/properties/errors", "importErrors");
        Route::Post("/import/aucs/properties/export", "exportImportErrors")->name("properties.export.import");
        //PDF
        Route::post('/property/pdf', 'downloadPDF')->name('properties.pdf');
        Route::get('/property/{property}/pdf', 'downloadShowPDF')->name('properties.showPdf');
    });

    /////////////////////////////////////////////
    /////////////// Report Routes /////////////
    /////////////////////////////////////////////

    Route::controller(\App\Http\Controllers\ReportController::class)->group(function() {
        //Reports
        Route::get('/reports', 'index')->name('reports.index');
    });

    /////////////////////////////////////////////
    /////////////// Request Routes /////////////
    /////////////////////////////////////////////

    Route::controller(\App\Http\Controllers\RequestsController::class)->group(function() {
        //Request
        Route::post('/request/transfer', 'transfer')->name('request.transfer');
        Route::post('/request/dispose', 'disposal')->name('request.disposal');
        Route::get('/request/{requests}/handle/{status}', 'handle')->name('request.handle');
        Route::post('/request/access', 'handleAccess')->name('request.access.handle');
        Route::get('/requests', 'index')->name('requests.index');
        Route::get('/requests/access', 'access')->name('requests.access');
    });

    /////////////////////////////////////////////
    /////////////// Supplier Routes /////////////
    /////////////////////////////////////////////

    Route::controller(\App\Http\Controllers\SupplierController::class)->group(function() {
        //Supplier
        Route::resource('/suppliers', 'App\Http\Controllers\SupplierController');
        Route::get('/supplier/pdf', 'downloadPDF')->name('suppliers.pdf');
        Route::get('/supplier/{supplier}/pdf', 'downloadShowPDF')->name('suppliers.showPdf');
        Route::get("/exportsuppliers", "export");
        Route::post('/search/suppliers/', 'search')->name('supplier.search');
        Route::post('/supplier/preview/', 'preview')->name('supplier.preview');
        //Exports
        Route::get("/exportsuppliers", "export");
    });

    /////////////////////////////////////////////
    /////////////// Transfer Routes /////////////
    /////////////////////////////////////////////

    Route::controller(\App\Http\Controllers\TransferController::class)->group(function() {
        //Transfers
        Route::get('/transfers', 'index')->name('transfers.index');
        Route::get('/asset/transfers', 'assets')->name('transfers.assets');
        Route::get('/accessory/transfers', 'accessories')->name('transfers.accessories');
    });

    Route::controller(\App\Http\Controllers\BackupController::class)->group(function() {
        //Database Backups Routes (Doesn't include import routes)
        Route::resource('/databasebackups', \App\Http\Controllers\BackupController::class);
        Route::get('/databasebackups/create/dbbackup', "createDB")->name('backupdb.create');
        Route::get('/databasebackups/create/backup', "createFull")->name('backup.create');
        Route::get('/databasebackups/clean/backups', "dbClean")->name('backup.clean');
        Route::get('/databasebackupdownload/{$file_name}', "download")->name('download.backup');
    });
    Route::controller(\App\Http\Controllers\StatusController::class)->group(function() {
        // status Routes (Doesn't include import routes)
        Route::resource('/status', 'App\Http\Controllers\StatusController');
    });
    Route::controller(\App\Http\Controllers\MiscellaneaController::class)->group(function() {
        //Miscellaneous
        Route::resource('/miscellaneous', "\App\Http\Controllers\MiscellaneaController");
        Route::post('/miscellaneous/{miscellanea}', "changeStatus")->name('miscellaneous.status');
        Route::post('/miscellaneous/comment/create', 'newComment')->name('miscellaneous.comment');
        Route::get('/miscellanea/bin', 'recycleBin')->name('miscellaneous.bin');
        Route::get('/miscellaneous/{miscellanea}/restore', 'restore')->name('miscellaneous.restore');
        Route::post('/miscellaneous/{miscellanea}/remove', 'forceDelete')->name('miscellaneous.remove');
        Route::post('/miscellanea/pdf', 'downloadPDF')->name('miscellaneous.pdf');
        Route::get('/miscellanea/{miscellanea}/pdf', 'downloadShowPDF')->name('miscellaneous.showPdf');
        Route::post('/miscellanea/filter', 'filter')->name('miscellanea.filter');
        Route::get('/miscellanea/filter/clear', 'clearFilter')->name('miscellanea.clear.filter');
        Route::get('/miscellanea/filter', 'filter')->name('miscellanea.filtered');
        //Exports
        Route::get("/exportmiscellaneous", "export");
        //Imports
        Route::Post("miscellanea/export-import-errors", "importErrors")->name("miscellanea-export.import");
        Route::post("/importmiscellaneous", "import");
        Route::Post("miscellaneous/create/ajax", "ajaxMany");

    });
    Route::controller(\App\Http\Controllers\LogController::class)->group(function() {
        //Logs
        Route::get("/logs", "index")->name("logs.index");
        Route::get("/logs/delete", "destroy")->name("logs.destroy");
        Route::get('/logs/filter/clear', "clearFilter")->name('logs.clear.filter');
        Route::post('/logs/filter', "filter")->name('logs.filter');
        Route::get('/logs/filter', "filter")->name('logs.filtered');
        //Exports
        Route::Post("/exportlogs", "export");

    });
    Route::controller(\App\Http\Controllers\SettingsController::class)->group(function() {
        //settings page
        Route::get("/settings", "index")->name("settings.view");
        Route::Post("/settings/accessories/export", "accessories")->name("settings.accessories");
        Route::Post("/settings/assets/export", "assets")->name("settings.assets");
        Route::Post("/settings/components/export", "components")->name("settings.components");
        Route::Post("/settings/miscellaneous/export", "miscellaneous")->name("settings.miscellaneous");
        Route::get("/settings/roles/create", "roleBoot")->name('role.boot');
    });
    Route::controller(\App\Http\Controllers\SettingsController::class)->group(function() {
        //settings page
        Route::get("/settings", "index")->name("settings.view");
        Route::Post("/settings/accessories/export", "accessories")->name("settings.accessories");
        Route::Post("/settings/assets/export", "assets")->name("settings.assets");
        Route::Post("/settings/components/export", "components")->name("settings.components");
        Route::Post("/settings/miscellaneous/export", "miscellaneous")->name("settings.miscellaneous");
        Route::get("/settings/roles/create", "roleBoot")->name('role.boot');
    });
    Route::controller(\App\Http\Controllers\ChartController::class)->group(function() {
        //Javascript pie charts for dashboard
        Route::get('chart/pie/locations', 'getPieChart');
        Route::get('chart/asset/values', 'getAssetValueChart');
        Route::get('chart/asset/audits', 'getAssetAuditChart');
    });
    Route::controller(\App\Http\Controllers\RoleController::class)->group(function() {
        //Roles
        Route::Post('/role/create', "store")->name('role.create');
        Route::Post('/role/sync', "roleSync")->name('role.sync');
        Route::Post('/role/delete/', "destroy")->name('role.destroy');
        Route::Get('/role/default', "default")->name('role.default');
    });

    //documentation link
    Route::get("/help/documentation", function() {
        return view('documentation.Documents');
    })->name('documentation.index');
    Route::get("/help/documentation/{section}", function() {
        return view('documentation.Documents');
    })->name('documentation.index.section');

});



