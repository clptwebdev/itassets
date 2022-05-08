<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

use App\Models\Location;

class AUC extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'location_id', 'purchased_date', 'depreciation', 'type', 'purchased_cost', 'user_id'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    //Returns the Location attached to the property
    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

   //Works out the depreciation value at the date that is passed through to the function
    //Use the Depreciation time to minus the depreication charge
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


    //Gets the building type in the table and displays it as a string
    // (1 = Freehold Land 2 = Freehold Buildings 3 = Leadsehold Land 4 = Leasehold Buildings)
    public function getType()
    {
        switch($this->type)
        {
            case 1:
                return "Freehold Land";
                break;
            case 2:
                return "Freehold Buildings";
                break;
            case 3:
                return "Leasehold Land";
                break;
            case 4:
                return "Leasehold Buildings";
                break;
            default:
                return "Unknown";
        }
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
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

    //Filters the properties that are based in the selected locations
    //$locations is an array of the location ids
    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function scopeSearchFilter($query, $search)
    {
        return $query->where('a_u_c_s.name', 'LIKE', "%{$search}%");
    }

    //////////////////////////////////////////////
    ////////////////Cache Functions///////////////
    //////////////////////////////////////////////

    public static function getCache($ids)
    {
        $auc_total = 0;
        $cost_total = 0;
        $dep_total = 0;

        $locations = Location::find($ids);

        foreach($locations as $location)
        {
            $id = $location->id;
            /* The Cache Values for the Location */
            if(! Cache::has("auc-L{$id}-total") &&
                ! Cache::has("auc-L{$id}-cost") &&
                ! Cache::has("auc-L{$id}-dep")
            )
            {
                AUC::updateLocationCache($location);
            }

            $auc_total += Cache::get("auc-L{$id}-total");
            $cost_total += Cache::get("auc-L{$id}-cost");
            $dep_total += Cache::get("auc-L{$id}-dep");
        }

        //Totals of the Assets
        Cache::rememberForever('auc-total', function() use ($auc_total) {
            return round($auc_total);
        });

        Cache::rememberForever('auc-cost', function() use ($cost_total) {
            return round($cost_total);
        });

        Cache::rememberForever('auc-dep', function() use ($dep_total) {
            return round($dep_total);
        });
    }

    public static function updateLocationCache(Location $location)
    {
        $loc_cost_total = 0;
        $loc_dep_total = 0;
        $id = $location->id;

        $aucs = AUC::whereLocationId($location->id)
            ->select('purchased_cost', 'purchased_date', 'depreciation')
            ->get()
            ->map(function($item, $key) {
                $item['depreciation_value'] = $item->depreciation_value();
                return $item;
            });

        //Get the Total Amount of Assets available for this location and set it in Cache
        $loc_total = $aucs->count();
        Cache::rememberForever("auc-L{$id}-total", function() use ($loc_total) {
            return $loc_total;
        });

        foreach($aucs as $auc)
        {
            $loc_cost_total += $auc->purchased_cost;
            $loc_dep_total += $auc->depreciation_value;
        }

        /* The Cache Values for the Location */
        Cache::set("auc-L{$id}-cost", round($loc_cost_total));
        Cache::set("auc-L{$id}-dep", round($loc_dep_total));
    }

    public static function updateCache()
    {
        //The Variables holding the total of Assets available to the User
        $auc_total = 0;
        $cost_total = 0;
        $dep_total = 0;

        foreach(Location::all() as $location)
        {
            $loc_cost_total = 0;
            $loc_dep_total = 0;
            $id = $location->id;

            $aucs = AUC::whereLocationId($location->id)
                ->select('purchased_cost', 'purchased_date', 'depreciation')
                ->get()
                ->map(function($item, $key) {
                    $item['depreciation_value'] = $item->depreciation_value();
                    return $item;
                });

            //Get the Total Amount of Assets available for this location and set it in Cache
            $loc_total = $aucs->count();
            Cache::rememberForever("property-L{$id}-total", function() use ($loc_total) {
                return $loc_total;
            });

            //Add the total to the Total amount of Assets
            $auc_total += $loc_total;

            foreach($aucs as $auc)
            {
                $loc_cost_total += $auc->purchased_cost;
                $loc_dep_total += $auc->depreciation_value;
            }

            /* The Cache Values for the Location */
            Cache::set("auc-L{$id}-cost", round($loc_cost_total));
            $cost_total += $loc_cost_total;
            Cache::set("auc-L{$id}-dep", round($loc_dep_total));
            $dep_total += $loc_dep_total;
        }
    }

}
