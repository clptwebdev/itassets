<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         'App\Models\Accessory' => 'App\Policies\AccessoryPolicy',
         'App\Models\Asset' => 'App\Policies\AssetPolicy',
         'App\Models\Component' => 'App\Policies\ComponentPolicy',
         'App\Models\User' => 'App\Policies\UserPolicy',
         'App\Models\Consumable' => 'App\Policies\ConsumablePolicy',
         'App\Models\AssetModel' => 'App\Policies\AssetModelPolicy',
         'App\Models\Location' => 'App\Policies\LocationPolicy',
         'App\Models\Comment' => 'App\Policies\CommentPolicy',
         'App\Models\Miscellanea' => 'App\Policies\MiscellaneaPolicy',
         'App\Models\Manufacturer' => 'App\Policies\ManufacturerPolicy',
         'App\Models\Supplier' => 'App\Policies\SupplierPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
