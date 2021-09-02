<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

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
    public function boot()
    {

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
    }
}
