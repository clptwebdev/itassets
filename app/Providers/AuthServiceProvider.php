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
