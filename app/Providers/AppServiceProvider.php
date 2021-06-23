<?php
namespace App\Providers;

use App\Models\Asset;
use Illuminate\Support\ServiceProvider;
use App\Models\Asset;

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
        view()->share("assetAmount" ,count(Asset::all()));
    }
}
