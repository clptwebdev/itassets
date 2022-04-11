<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Cache;

class Accessory extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'asset_tag', 'model', 'serial_no', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'status_id', 'order_no', 'warranty', 'location_id', 'room', 'notes', 'manufacturer_id', 'photo_id', 'depreciation_id', 'user_id'];

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

    public function depreciation()
    {
        return $this->belongsTo(Depreciation::class);
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
        return $query->where('accessories.name', 'LIKE', "%{$search}%")
            ->orWhere('accessories.serial_no', 'LIKE', "%{$search}%");
    }

    public function scopeExportFilterStatus($query, $status, $category, $location)
    {
        $pivot = $this->category()->getTable();

        return $query->whereHas('category', function($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        })
            ->orWhereIn('status_id', $status)
            ->orWhereIn('location_id', $location);

    }

    public function depreciation_value_by_date($date)
    {
        if(! $this->depreciation == null)
        {
            $age = $date->floatDiffInYears($this->purchased_date);
            $percent = 100 / 3;
            $percentage = floor($age) * $percent;
            $value = $this->purchased_cost * ((100 - $percentage) / 100);

            if($value < 0)
            {
                return 0;
            } else
            {
                return $value;
            }
        } else
        {
            return 0;
        }
    }

    public function depreciation_value()
    {
        $eol = Carbon::parse($this->purchased_date)->addYears($this->depreciation_years());
        if($eol->isPast())
        {
            return 0;
        } else
        {
            $age = Carbon::now()->floatDiffInYears($this->purchased_date);
            $percent = 100 / $this->depreciation_years();
            $percentage = floor($age) * $percent;
            $dep = $this->purchased_cost * ((100 - $percentage) / 100);

            return $dep;
        }

    }

    public function depreciation_years()
    {
        return $this->depreciation->years ?? 0;
    }

    public static function updateCache()
    {
        //The Variables holding the total of Accessories available to the User
        $accessories_total = 0;
        $accessories_cost_total = 0;
        $accessories_dep_total = 0;
        $accessories_deployed_total = 0;

        foreach(Location::all() as $location)
        {
            $id = $location->id;

            //Variables to Hold the Accessories for that Location
            $accessories_loc_total = 0;
            $accessories_cost = 0;
            $accessories_dep = 0;
            $accessories_deployed = 0;

            $accessories = Accessory::whereLocationId($id)
                ->with('status', 'depreciation')
                ->select('purchased_cost', 'purchased_date', 'depreciation_id', 'status_id', 'location_id')
                ->get()
                ->map(function($item, $key) {
                    $item['depreciation_value'] = $item->depreciation_value();
                    $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;

                    return $item;
                });

            $accessories_loc_total = $accessories->count();
            Cache::rememberForever("accessories-L{$id}-total", function() use ($accessories_loc_total) {
                return $accessories_loc_total;
            });

            $accessories_total += $accessories_loc_total;

            foreach($accessories as $accessory)
            {
                $accessories_cost += $accessory->purchased_cost;
                $accessories_dep += $accessory->depreciation_value;
                if($accessory->deployable !== 1)
                {
                    $accessories_deployed++;
                }
            }

            Cache::set("accessories-L{$id}-cost", round($accessories_cost));
            $accessories_cost_total += $accessories_cost;
            Cache::set("accessories-L{$id}-depr", round($accessories_dep));
            $accessories_dep_total += $accessories_dep;
            Cache::set("accessories-L{$id}-deploy", round($accessories_deployed));
            $accessories_deployed_total += $accessories_deployed;
        }

        //Accessories

        Cache::rememberForever('accessories_total', function() use ($accessories_total) {
            return round($accessories_total);
        });

        Cache::rememberForever('accessories_cost', function() use ($accessories_cost_total) {
            return round($accessories_cost_total);
        });

        Cache::rememberForever('accessories_dep', function() use ($accessories_dep_total) {
            return round($accessories_dep_total);
        });

        Cache::rememberForever('accessories_deploy', function() use ($accessories_deployed_total) {
            return round($accessories_deployed_total);
        });
    }

    public static function updateLocationCache(Location $location)
    {

        $accessories_cost = 0;
        $accessories_dep = 0;
        $accessories_deployed = 0;

        $id = $location->id;

        $accessories = Accessory::whereLocationId($id)
            ->with('status', 'depreciation')
            ->select('purchased_cost', 'purchased_date', 'depreciation_id', 'status_id', 'location_id')
            ->get()
            ->map(function($item, $key) {
                $item['depreciation_value'] = $item->depreciation_value();
                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;

                return $item;
            });

        $accessories_loc_total = $accessories->count();
        Cache::rememberForever("accessories-L{$id}-total", function() use ($accessories_loc_total) {
            return $accessories_loc_total;
        });

        foreach($accessories as $accessory)
        {
            $accessories_cost += $accessory->purchased_cost;
            $accessories_dep += $accessory->depreciation_value;
            if($accessory->deployable !== 1)
            {
                $accessories_deployed++;
            }
        }

        Cache::set("accessories-L{$id}-cost", round($accessories_cost));
        Cache::set("accessories-L{$id}-depr", round($accessories_dep));
        Cache::set("accessories-L{$id}-deploy", round($accessories_deployed));
    }

    public static function getCache($ids)
    {
        //The Variables holding the total of Accessories available to the User
        $accessories_total = 0;
        $accessories_cost_total = 0;
        $accessories_dep_total = 0;
        $accessories_deployed_total = 0;

        $locations = Location::find($ids);

        foreach($locations as $location)
        {
            $id = $location->id;
            /* The Cache Values for the Location */
            if(! Cache::has("accessories-L{$id}-total") &&
                ! Cache::has("accessories-L{$id}-cost") &&
                ! Cache::has("accessories-L{$id}-dep") &&
                ! Cache::has("accessories-L{$id}-deploy")
            )
            {
                Accessory::updateLocationCache($location);
            }

            $accessories_total += Cache::get("accessories-L{$id}-total");
            $accessories_cost_total += Cache::get("accessories-L{$id}-cost");
            $accessories_dep_total += Cache::get("accessories-L{$id}-depr");
            $accessories_deployed_total += Cache::get("accessories-L{$id}-deploy");
        }

        //Accessories

        Cache::rememberForever('accessories_total', function() use ($accessories_total) {
            return round($accessories_total);
        });

        Cache::rememberForever('accessories_cost', function() use ($accessories_cost_total) {
            return round($accessories_cost_total);
        });

        Cache::rememberForever('accessories_dep', function() use ($accessories_dep_total) {
            return round($accessories_dep_total);
        });

        Cache::rememberForever('accessories_deploy', function() use ($accessories_deployed_total) {
            return round($accessories_deployed_total);
        });
    }

    public static function expenditure($year, $locations)
    {
        $expenditure = 0;
        $accessories = Accessory::whereIn('location_id', $locations)->whereYear('purchased_date', $year)->select('donated', 'purchased_cost', 'location_id')->get();
        foreach($accessories as $accessory)
        {
            if($accessory->donated !== 1)
            {
                $expenditure += $accessory->purchased_cost;
            }
        }

        return $expenditure;
    }

    public static function donations($year, $locations)
    {
        $donations = 0;
        $accessories = Accessory::whereIn('location_id', $locations)->whereYear('purchased_date', $year)->select('donated', 'purchased_cost', 'location_id')->get();
        foreach($accessories as $accessory)
        {
            if($accessory->donated === 1)
            {
                $donations += $accessory->purchased_cost;
            }
        }

        return $donations;

    }

}
