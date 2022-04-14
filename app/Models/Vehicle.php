<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;


class Vehicle extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
            fn($value) => strtolower($value),
        );
    }

    public function registration(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
            fn($value) => ucwords($value),
        );
    }

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }
    //Filters the properties that are based in the selected locations
    //$locations is an array of the location ids
    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function depreciation_value_by_date($date)
    {
        $age = $date->floatDiffInYears($this->purchased_date);
        $percent = 100 / $this->depreciation;
        $percentage = floor($age) * $percent;
        $value = $this->purchased_cost * ((100 - $percentage) / 100);

        if($value < 0)
        {
            return 0;
        } else
        {
            return $value;
        }
    }

    public function depreciation_value()
    {
        $eol = \Carbon\Carbon::parse($this->purchased_date)->addYears($this->depreciation);
        if($eol->isPast())
        {
            return 0;
        } else
        {
            $age = Carbon::now()->floatDiffInYears($this->purchased_date);
            $percent = 100 / $this->depreciation;
            $percentage = floor($age) * $percent;
            $dep = $this->purchased_cost * ((100 - $percentage) / 100);

            return $dep;
        }

    }

    /////////////////////////////////////////////////
    ///////////////////Filters///////////////////////
    /////////////////////////////////////////////////

    //Filters out the property by the date acquired/purchased. Start = the Start Date End = the End Date
    public function scopePurchaseFilter($query, $start, $end)
    {
        $query->whereBetween('purchased_date', [$start, $end]);
    }

    //Filters the porperty thats value is between two values set in one string
    //These variables are passed from the sldier on the filter
    public function scopeCostFilter($query, $min, $max)
    {
        $query->whereBetween('purchased_cost', [$min, $max]);
    }

    public function scopeSearchFilter($query, $search)
    {
        return $query->where('vehicles.name', 'LIKE', "%{$search}%")
            ->orWhere('vehicles.registration', 'LIKE', "%{$search}%");
    }

    public function scopeCategoryFilter($query, $category)
    {
        $pivot = $this->category()->getTable();

        $query->whereHas('category', function($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        });
    }

     //////////////////////////////////////////////
    ////////////////Cache Functions///////////////
    //////////////////////////////////////////////

    public static function getCache($ids)
    {
        $count_total = 0;
        $cost_total = 0;
        $dep_total = 0;

        $locations = Location::find($ids);

        foreach($locations as $location)
        {
            $id = $location->id;
            /* The Cache Values for the Location */
            if(! Cache::has("vehicle-L{$id}-total") &&
                ! Cache::has("vehicle-L{$id}-cost") &&
                ! Cache::has("vehicle-L{$id}-dep")
            )
            {
                vehicle::updateLocationCache($location);
            }

            $count_total += Cache::get("vehicle-L{$id}-total");
            $cost_total += Cache::get("vehicle-L{$id}-cost");
            $dep_total += Cache::get("vehicle-L{$id}-dep");
        }

        //Totals of the Assets
        Cache::rememberForever('vehicle-total', function() use ($count_total) {
            return round($count_total);
        });

        Cache::rememberForever('vehicle-cost', function() use ($cost_total) {
            return round($cost_total);
        });

        Cache::rememberForever('vehicle-dep', function() use ($dep_total) {
            return round($dep_total);
        });
    }

    public static function updateLocationCache(Location $location)
    {
        $loc_cost_total = 0;
        $loc_dep_total = 0;
        $id = $location->id;

        $vehicles = Vehicle::whereLocationId($location->id)
            ->select('purchased_cost', 'purchased_date', 'depreciation')
            ->get()
            ->map(function($item, $key) {
                $item['depreciation_value'] = $item->depreciation_value();
                return $item;
            });

        //Get the Total Amount of Assets available for this location and set it in Cache
        $loc_total = $vehicles->count();
        Cache::rememberForever("vehicle-L{$id}-total", function() use ($loc_total) {
            return $loc_total;
        });

        foreach($vehicles as $vehicle)
        {
            $loc_cost_total += $vehicle->purchased_cost;
            $loc_dep_total += $vehicle->depreciation_value;
        }

        /* The Cache Values for the Location */
        Cache::set("vehicle-L{$id}-cost", round($loc_cost_total));
        Cache::set("vehicle-L{$id}-dep", round($loc_dep_total));
    }

    public static function updateCache()
    {
        //The Variables holding the total of Assets available to the User
        $count_total = 0;
        $cost_total = 0;
        $dep_total = 0;

        foreach(Location::all() as $location)
        {
            $loc_cost_total = 0;
            $loc_dep_total = 0;
            $id = $location->id;

            $vehicles = Vehicle::whereLocationId($location->id)
                ->select('purchased_cost', 'purchased_date', 'depreciation')
                ->get()
                ->map(function($item, $key) {
                    $item['depreciation_value'] = $item->depreciation_value();
                    return $item;
                });

            //Get the Total Amount of Assets available for this location and set it in Cache
            $loc_total = $machineries->count();
            Cache::rememberForever("vehicle-L{$id}-total", function() use ($loc_total) {
                return $loc_total;
            });

            //Add the total to the Total amount of Assets
            $count_total += $loc_total;

            foreach($vehicles as $vehicle)
            {
                $loc_cost_total += $vehicle->purchased_cost;
                $loc_dep_total += $vehicle->depreciation_value;
            }

            /* The Cache Values for the Location */
            Cache::set("vehicle-L{$id}-cost", round($loc_cost_total));
            $cost_total += $loc_cost_total;
            Cache::set("vehicle-L{$id}-dep", round($loc_dep_total));
            $dep_total += $loc_dep_total;
        }
    }


}
