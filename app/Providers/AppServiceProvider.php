<?php
namespace App\Providers;

use App\Models\Asset;
use App\Models\User;
use App\Models\Supplier;
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
        User::observe(\App\Observers\UserObserver::class);
        Asset::observe(\App\Observers\AssetObserver::class);
        Supplier::observe(\App\Observers\SupplierObserver::class);

        Relation::morphMap([
            'user' => 'App\Models\User',
            'asset' => 'App\Models\Asset',
            'consumable' => 'App\Models\Consumable',
            'accessory' => 'App\Models\Accessory',
        ]);
    }
}
