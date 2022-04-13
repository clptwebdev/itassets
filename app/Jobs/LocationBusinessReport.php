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

use \Carbon\Carbon;

class LocationBusinessReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $location;
    protected $user;
    public $path;
    
    public function __construct(Location $location, $user, $path)
    {
        $this->location = $location;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $location = $this->location;
        $user = $this->user;
        $path = $this->path;

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
                        ->select('name', 'purchased_cost', 'purchased_date', 'depreciation')
                        ->get();
        //Get the Properties in the Archive Table
        $property_archived = Archive::where('model_type', '=', 'property')
                        ->where('location_id', '=', $location->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
                        ->get(); 

        //Create an empty collection to pu the merge properties in to
        $property = Collection::empty();
        //merge the assets and the archives
        $property_merged = collect([$property_assets, $property_archived]);
        foreach($property_merged as $property_merge){
            foreach($property_merge as $property_item){
                $property->push($property_item);
            }
        }

        //Get Assets Under Construction - AUC does not have an Archive Feature
        $auc = AUC::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();

        $ffe_assets = FFE::where('location_id', '=', $location->id)
                        ->select('name', 'purchased_cost', 'purchased_date', 'depreciation')
                        ->get();
        
        $ffe_disposed = Archive::where('model_type', '=', 'FFE')
                        ->where('location_id', '=', $location->id)
                        ->whereBetween('date', [$startDate, $endDate])
                        ->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')
                        ->get();

        
        $machines = Machinery::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();
        $vehicle = Vehicle::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();
        $assets = Asset::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'asset_model', 'donated')->get();
        $accessories = Accessory::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation_id', 'donated')->get();
        $software = Software::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation', 'donated')->get();

        $ffe = Collection::empty();
        $ffe_merged = collect([$ffe_assets, $ffe_disposed]);
        foreach($ffe_merged as $ffe_merge){
            foreach($ffe_merge as $ffe_item){
                $ffe->push($ffe_item);
            }
        }

        $merged = collect([$accessories, $assets]);
        $computers = Collection::empty();
        //foreach $model then Foreach $item Push to a single collection
        foreach($merged as $merge)
        {
            foreach($merge as $item)
            {
                $computers->push($item);
            }
        }


        \Maatwebsite\Excel\Facades\Excel::store(new BusinessExport($computers, $property, $ffe, $auc, $machines, $vehicle), $path);

        //Notify User that there report is complete
        
        
    }
}