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
use App\Models\AUC;
use App\Models\Machinery;
use App\Models\Vehicle;
use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Software;
use Illuminate\Database\Eloquent\Collection;
use App\Exports\BusinessExport;
use App\Models\Location;

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
        
        $property = Property::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();
        $ffe = FFE::where('location_id', '=', $location->id)->select('name', 'purchased_cost', 'purchased_date', 'depreciation_id')->get();
        $ffe_disposed = Archive::where('model_type', '=', 'FFE')->select('name', 'purchased_cost', 'purchased_date', 'archived_cost', 'depreciation')->get();
        $auc = AUC::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();
        $machines = Machinery::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();
        $vehicle = Vehicle::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation')->get();
        $assets = Asset::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'asset_model', 'donated')->get();
        $accessories = Accessory::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation_id', 'donated')->get();
        $software = Software::locationFilter($location->pluck('id')->toArray())->select('name', 'purchased_cost', 'purchased_date', 'depreciation', 'donated')->get();

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