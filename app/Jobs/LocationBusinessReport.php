<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Property;
use App\Models\FFE;
use App\Models\Archive;
use App\Models\AUC;
use App\Models\Machinery;
use App\Models\Vehicle;
use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Software;
use Illuminate\Database\Eloquent\Collection;
use App\Exports\BusinessExport;
use App\Models\Location;
use App\Models\Setting;

use App\Notifications\SendBusinessReport;
use App\Models\User;

use \Carbon\Carbon;

class LocationBusinessReport implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $location;
    protected $user;
    public $path;
    public $route;

    public function __construct(Location $location, $user, $path, $route)
    {
        $this->location = $location;
        $this->user = $user;
        $this->path = $path;
        $this->route = $route;
    }

    public function handle()
    {
        $location = $this->location;
        $user = $this->user;
        $path = $this->path;
        $route = $this->route;

        $threshold_setting = Setting::where('name', '=', 'asset_threshold')->first();
        $threshold = $threshold_setting->value ?? 0;

        $now = Carbon::now();
        $startDate = Carbon::parse('09/01/' . $now->format('Y'));
        $endDate = Carbon::parse('08/31/' . $now->addYear()->format('Y'));
        if(! $startDate->isPast())
        {
            $startDate->subYear();
            $endDate->subYear();
        }

        //Get Properties in the Porperties table
        $property_assets = Property::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'purchased_cost', 'purchased_date', 'depreciation')
            ->get();
        //Get the Properties in the Archive Table
        $property_archived = Archive::where('model_type', '=', 'property')
            ->where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        //Create an empty collection to pu the merge properties in to
        $property = Collection::empty();
        //merge the assets and the archives
        $property_merged = collect([$property_assets, $property_archived]);
        foreach($property_merged as $property_merge)
        {
            foreach($property_merge as $property_item)
            {
                $property->push($property_item);
            }
        }

        //Get Assets Under Construction - AUC does not have an Archive Feature
        $auc = AUC::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'purchased_cost', 'purchased_date', 'depreciation')
            ->get();

        //Get FFE in the f_f_e_s table
        $ffe_assets = FFE::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'purchased_cost', 'purchased_date', 'depreciation')
            ->get();
        //Get FFE in the archived table
        $ffe_disposed = Archive::where('model_type', '=', 'FFE')
            ->where('purchased_cost', '>=', $threshold)
            ->where('location_id', '=', $location->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        $ffe = Collection::empty();
        $ffe_merged = collect([$ffe_assets, $ffe_disposed]);
        foreach($ffe_merged as $ffe_merge)
        {
            foreach($ffe_merge as $ffe_item)
            {
                $ffe->push($ffe_item);
            }
        }

        //Get Plant and Machinery
        $machine_assets = Machinery::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'purchased_cost', 'purchased_date', 'depreciation')
            ->get();

        //Get FFE in the archived table
        $machine_disposed = Archive::where('model_type', '=', 'machinery')
            ->where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        $machinery = Collection::empty();
        $machine_merged = collect([$machine_assets, $machine_disposed]);
        foreach($machine_merged as $machine_merge)
        {
            foreach($machine_merge as $machine_item)
            {
                $machinery->push($machine_item);
            }
        }

        $vehicle_assets = Vehicle::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'registration', 'purchased_cost', 'purchased_date', 'depreciation')
            ->get();

        //Get Vehicles in the archived table
        $vehicle_disposed = Archive::where('model_type', '=', 'vehicles')
            ->where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        $vehicles = Collection::empty();
        $vehicles_merged = collect([$vehicle_assets, $vehicle_disposed]);
        foreach($vehicles_merged as $vehicle_merge)
        {
            foreach($vehicle_merge as $vehicle_item)
            {
                $vehicles->push($vehicle_item);
            }
        }

        $assets = Asset::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'asset_tag', 'purchased_cost', 'purchased_date', 'asset_model', 'donated')
            ->get();

        //Get Vehicles in the archived table
        $assets_disposed = Archive::where('model_type', '=', 'asset')
            ->where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'asset_tag', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        $accessories = Accessory::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'asset_tag', 'purchased_cost', 'purchased_date', 'depreciation_id', 'donated')
            ->get();

        //Get Vehicles in the archived table
        $accessories_disposed = Archive::where('model_type', '=', 'accessory')
            ->where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'asset_tag', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        $merged = collect([$accessories, $assets, $assets_disposed, $accessories_disposed]);
        $computers = Collection::empty();
        //foreach $model then Foreach $item Push to a single collection
        foreach($merged as $merge)
        {
            foreach($merge as $item)
            {
                $computers->push($item);
            }
        }

        $software_assets = Software::where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->select('name', 'purchased_cost', 'purchased_date', 'depreciation', 'donated')
            ->get();

        //Get Vehicles in the archived table
        $software_disposed = Archive::where('model_type', '=', 'software')
            ->where('location_id', '=', $location->id)
            ->where('purchased_cost', '>=', $threshold)
            ->whereBetween('date', [$startDate, $endDate])
            ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
            ->get();

        $software_merged = collect([$software_assets, $software_disposed]);
        $softwares = Collection::empty();
        //foreach $model then Foreach $item Push to a single collection
        foreach($software_merged as $software_merge)
        {
            foreach($software_merge as $software_item)
            {
                $softwares->push($software_item);
            }
        }

        \Maatwebsite\Excel\Facades\Excel::store(new BusinessExport($computers, $property, $ffe, $auc, $machinery, $vehicles, $softwares), $path);

        //Notify User that there report is complete

<<<<<<< HEAD
        \Notification::route('mail', auth()->user()->email)->notifyNow(new SendBusinessReport(auth()->user(), $location, $route));
        //auth(->user()->notify(new App\Notifcations\SendBusinessReport))

        
        
=======
>>>>>>> bb691c54b97c5a3ef5ae3dda7ec01b4f04883f1e
    }

}
