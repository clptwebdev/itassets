<?php

namespace App\Providers;

use App\Models\AUC;
use App\Models\FFE;
use App\Models\Machinery;
use App\Models\Property;
use App\Models\Software;
use App\Models\Vehicle;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Manufacturer;
use App\Models\Component;
use App\Models\Accessory;
use App\Models\Consumable;
use App\Models\Location;
use App\Models\Miscellanea;
use App\Models\Backup;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            'SocialiteProviders\\Azure\\AzureExtendSocialite@handle',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],
        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(\App\Observers\UserObserver::class);
        Asset::observe(\App\Observers\AssetObserver::class);
        Accessory::observe(\App\Observers\AccessoryObserver::class);
        Supplier::observe(\App\Observers\SupplierObserver::class);
        Manufacturer::observe(\App\Observers\ManufacturerObserver::class);
        Component::observe(\App\Observers\ComponentObserver::class);
        Consumable::observe(\App\Observers\ConsumableObserver::class);
        AssetModel::observe(\App\Observers\AssetModelObserver::class);
        Location::observe(\App\Observers\LocationObserver::class);
        Miscellanea::observe(\App\Observers\MiscellaneaObserver::class);
        Backup::observe(\App\Observers\BackupObserver::class);
        AUC::observe(\App\Observers\AUCObserver::class);
        FFE::observe(\App\Observers\FFEObserver::class);
        Property::observe(\App\Observers\PropertyObserver::class);
        Machinery::observe(\App\Observers\MachineryObserver::class);
        Vehicle::observe(\App\Observers\VehicleObserver::class);
        Software::observe(\App\Observers\SoftwareObserver::class);
    }

}
