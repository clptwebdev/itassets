<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use ConsoleTVs\Charts\Registrar as Charts;

class AppServiceProvider extends ServiceProvider
{
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
        ]);

        $charts->register([
            \App\Charts\ExpChart::class,
            \App\Charts\DepChart::class,
            \App\Charts\DepreciationChart::class,
            \App\Charts\ExpenditureChart::class
        ]);
    }
}
