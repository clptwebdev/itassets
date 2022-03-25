<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use ConsoleTVs\Charts\Registrar as Charts;

class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    {
        Paginator::useBootstrap();
        Relation::morphMap([
            'user' => 'App\Models\User',
            'asset' => 'App\Models\Asset',
            'consumable' => 'App\Models\Consumable',
            'component' => 'App\Models\Component',
            'accessory' => 'App\Models\Accessory',
            'assetModel' => 'App\Models\AssetModel',
            'location' => 'App\Models\Location',
            'supplier' => 'App\Models\Supplier',
            'miscellanea' => 'App\Models\Miscellanea',
            'archive' => 'App\Models\Archive',
            'auc' => 'App\Models\AUC',
            'backup' => 'App\Models\Backup',
            'category' => 'App\Models\Category',
            'comment' => 'App\Models\Comment',
            'depreciation' => 'App\Models\Depreciation',
            'field' => 'App\Models\Field',
            'fieldset' => 'App\Models\Field',
            'locationUser' => 'App\Models\LocationUser',
            'log' => 'App\Models\Log',
            'manufacturer' => 'App\Models\Manufacturer',
            'permission' => 'App\Models\Permission',
            'photo' => 'App\Models\Photo',
            'property' => 'App\Models\Property',
            'report' => 'App\Models\Report',
            'requests' => 'App\Models\Requests',
            'role' => 'App\Models\Role',
            'status' => 'App\Models\Status',
            'transfer' => 'App\Models\Transfer',
        ]);

        $charts->register([
            \App\Charts\ExpChart::class,
            \App\Charts\DepChart::class,
            \App\Charts\DepreciationChart::class,
            \App\Charts\ExpenditureChart::class,
            \App\Charts\TotalExpenditure::class,
        ]);
    }

}
