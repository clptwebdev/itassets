<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Cache;

class Component extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'serial_no', 'purchased_date', 'purchased_cost', 'supplier_id', 'status_id', 'order_no', 'warranty', 'location_id', 'notes', 'manufacturer_id', 'photo_id'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function notes(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photo_id');
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, 'commentables');
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

    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function depreciation()
    {
        return $this->belongsTo(Depreciation::class);
    }

    public function scopeStatusFilter($query, $status)
    {
        return $query->whereIn('status_id', $status);
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
        return $query->where('components.name', 'LIKE', "%{$search}%")
            ->orWhere('components.serial_no', 'LIKE', "%{$search}%");
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

    public static function updateCache()
    {
        //The Variables holding the total of Accessories available to the User
        $components_total = 0;
        $components_cost_total = 0;
        $components_deployed_total = 0;

        foreach(Location::all() as $location)
        {
            $id = $location->id;

            //Variables to Hold the Accessories for that Location
            $components_cost = 0;
            $components_deployed = 0;

            $components = Component::whereLocationId($id)
                ->select('purchased_cost', 'status_id')
                ->get()
                ->map(function($item, $key) {
                    $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;

                    return $item;
                });

            $components_loc_total = $components->count();
            Cache::rememberForever("components-L{$id}-total", function() use ($components_loc_total) {
                return $components_loc_total;
            });

            $components_total += $components_loc_total;

            foreach($components as $component)
            {
                $components_cost += $component->purchased_cost;
                if($component->deployable != 1)
                {
                    $components_deployed++;
                }
            }

            Cache::set("components-L{$id}-cost", round($components_cost));
            Cache::set("components-L{$id}-deploy", round($components_deployed));
        }

        /* Components Calcualtions */

        Cache::rememberForever('components_total', function() use ($components_total) {
            return round($components_total);
        });

        Cache::rememberForever('components_cost', function() use ($components_cost_total) {
            return round($components_cost_total);
        });

        Cache::rememberForever('components_deploy', function() use ($components_deployed_total) {
            return round($components_deployed_total);
        });
    }

    public static function updateLocationCache(Location $location)
    {
        $id = $location->id;

        //Variables to Hold the Accessories for that Location
        $components_cost = 0;
        $components_deployed = 0;

        $components = Component::whereLocationId($id)
            ->select('purchased_cost', 'status_id')
            ->get()
            ->map(function($item, $key) {
                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;

                return $item;
            });

        $components_loc_total = $components->count();
        Cache::rememberForever("components-L{$id}-total", function() use ($components_loc_total) {
            return $components_loc_total;
        });

        foreach($components as $component)
        {
            $components_cost += $component->purchased_cost;
            if($component->deployable != 1)
            {
                $components_deployed++;
            }
        }

        Cache::set("components-L{$id}-cost", round($components_cost));
        Cache::set("components-L{$id}-deploy", round($components_deployed));
    }

    public static function getCache($ids)
    {
        //The Variables holding the total of Accessories available to the User
        $components_total = 0;
        $components_cost_total = 0;
        $components_deployed_total = 0;

        $locations = Location::find($ids);

        foreach($locations as $location)
        {
            $id = $location->id;
            /* The Cache Values for the Location */
            if(! Cache::has("components-L{$id}-total") &&
                ! Cache::has("components-L{$id}-cost") &&
                ! Cache::has("components-L{$id}-deploy")
            )
            {
                Component::updateLocationCache($location);
            }

            $components_total += Cache::get("components-L{$id}-total");
            $components_cost_total += Cache::get("components-L{$id}-cost");
            $components_deployed_total += Cache::get("components-L{$id}-deploy");
        }

        /* Components Calcualtions */

        Cache::rememberForever('components_total', function() use ($components_total) {
            return round($components_total);
        });

        Cache::rememberForever('components_cost', function() use ($components_cost_total) {
            return round($components_cost_total);
        });

        Cache::rememberForever('components_deploy', function() use ($components_deployed_total) {
            return round($components_deployed_total);
        });
    }

    public static function expenditure($year, $locations)
    {
        $expenditure = 0;
        $components = Component::whereIn('location_id', $locations)->whereYear('purchased_date', $year)->select('purchased_cost', 'location_id')->get();
        foreach($components as $component)
        {
            $expenditure += $component->purchased_cost;
        }

        return $expenditure;
    }

}
