<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class FFE extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    /////////////////////////////////////////////////
    //////////////// Relationships //////////////////
    /////////////////////////////////////////////////

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photo_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }

    public function category()
    {
        return $this->morphToMany(Category::class, 'cattable');
    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    /////////////////////////////////////////////////
    //////////////// Finance Functions //////////////
    /////////////////////////////////////////////////

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

    /////////////////////////////////////////////////
    ///////////////////Filters///////////////////////
    /////////////////////////////////////////////////

    public function scopeStatusFilter($query, $status)
    {
        return $query->whereIn('status_id', $status);
    }

    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function scopeCategoryFilter($query, $category)
    {
        $pivot = $this->category()->getTable();

        $query->whereHas('category', function($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        });
    }

    public function scopeCostFilter($query, $min, $max)
    {
        $query->whereBetween('purchased_cost', [$min, $max]);
    }

    public function scopePurchaseFilter($query, $start, $end)
    {
        $query->whereBetween('purchased_date', [$start, $end]);
    }

    public function scopeSearchFilter($query, $search)
    {
        return $query->where('f_f_e_s.name', 'LIKE', "%{$search}%")
            ->orWhere('f_f_e_s.serial_no', 'LIKE', "%{$search}%");
    }

    //////////////////////////////////////////////
    ////////////////Cache Functions///////////////
    //////////////////////////////////////////////

    public static function getCache($ids)
    {
        $ffe_total = 0;
        $cost_total = 0;
        $dep_total = 0;

        $locations = Location::find($ids);

        foreach($locations as $location)
        {
            $id = $location->id;
            /* The Cache Values for the Location */
            if(! Cache::has("ffe-L{$id}-total") &&
                ! Cache::has("ffe-L{$id}-cost") &&
                ! Cache::has("ffe-L{$id}-dep")
            )
            {
                FFE::updateLocationCache($location);
            }

            $ffe_total += Cache::get("ffe-L{$id}-total");
            $cost_total += Cache::get("ffe-L{$id}-cost");
            $dep_total += Cache::get("ffe-L{$id}-dep");
        }

        //Totals of the Assets
        Cache::rememberForever('ffe_total', function() use ($ffe_total) {
            return round($ffe_total);
        });

        Cache::rememberForever('ffe_cost', function() use ($cost_total) {
            return round($cost_total);
        });

        Cache::rememberForever('ffe_dep', function() use ($dep_total) {
            return round($dep_total);
        });
    }

    public static function updateLocationCache(Location $location)
    {
        $loc_cost_total = 0;
        $loc_dep_total = 0;
        $id = $location->id;

        $ffes = FFE::whereLocationId($location->id)
            ->select('purchased_cost', 'purchased_date', 'depreciation')
            ->get()
            ->map(function($item, $key) {
                $item['depreciation_value'] = $item->depreciation_value();
                return $item;
            });

        //Get the Total Amount of Assets available for this location and set it in Cache
        $loc_total = $ffes->count();
        Cache::rememberForever("ffe-L{$id}-total", function() use ($loc_total) {
            return $loc_total;
        });

        foreach($ffes as $ffe)
        {
            $loc_cost_total += $ffe->purchased_cost;
            $loc_dep_total += $ffe->depreciation_value;
        }

        /* The Cache Values for the Location */
        Cache::set("ffe-L{$id}-cost", round($loc_cost_total));
        Cache::set("ffe-L{$id}-dep", round($loc_dep_total));
    }

    public static function updateCache()
    {
        //The Variables holding the total of Assets available to the User
        $ffe_total = 0;
        $cost_total = 0;
        $dep_total = 0;

        foreach(Location::all() as $location)
        {
            $loc_cost_total = 0;
            $loc_dep_total = 0;
            $id = $location->id;

            $ffes = FFE::whereLocationId($location->id)
                ->select('purchased_cost', 'purchased_date', 'depreciation')
                ->get()
                ->map(function($item, $key) {
                    $item['depreciation_value'] = $item->depreciation_value();
                    return $item;
                });

            //Get the Total Amount of Assets available for this location and set it in Cache
            $loc_total = $ffes->count();
            Cache::rememberForever("ffe-L{$id}-total", function() use ($loc_total) {
                return $loc_total;
            });

            //Add the total to the Total amount of Assets
            $ffe_total += $loc_total;

            foreach($ffes as $ffe)
            {
                $loc_cost_total += $ffe->purchased_cost;
                $loc_dep_total += $ffe->depreciation_value;
            }

            /* The Cache Values for the Location */
            Cache::set("ffe-L{$id}-cost", round($loc_cost_total));
            $cost_total += $loc_cost_total;
            Cache::set("ffe-L{$id}-dep", round($loc_dep_total));
            $dep_total += $loc_dep_total;
        }
    }

}
