<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Cache;

class Asset extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'asset_tag', 'asset_model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id', 'order_no', 'warranty', 'location_id', 'room', 'user_id', 'audit_date', 'notes'];

    //dates

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->with('photo');
    }

    public function user()
    {
        return $this->belongsTo(User::class);

    }

    public function model()
    {
        return $this->belongsTo(AssetModel::class, 'asset_model', 'id')->with('manufacturer')->with('depreciation');
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class)->withPivot('value');
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function category()
    {
        return $this->morphToMany(Category::class, 'cattable');
    }

    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function scopeAssetModelFilter($query, $assetModel)
    {
        return $query->whereIn('asset_model', $assetModel);
    }

    public function scopeSearchFilter($query, $search)
    {
        return $query->where('assets.name', 'LIKE', "%{$search}%")
            ->orWhere('assets.asset_tag', 'LIKE', "%{$search}%")
            ->orWhere('assets.serial_no', 'LIKE', "%{$search}%");
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

    public function scopePurchaseFilter($query, $start, $end)
    {
        $query->whereBetween('purchased_date', [$start, $end]);
    }

    public function scopeAuditFilter($query, $date)
    {
        switch($date)
        {
            case 1:
                $query->where('audit_date', '<', \Carbon\Carbon::now()->toDateString());
                break;
            case 2:
                $date = \Carbon\Carbon::now()->addDays(30);
                $query->whereBetween('audit_date', [\Carbon\Carbon::now(), $date]);
                break;
            case 3:
                $date = \Carbon\Carbon::now()->addMonths(3);
                $query->whereBetween('audit_date', [\Carbon\Carbon::now(), $date]);
                break;
            case 4:
                $date = \Carbon\Carbon::now()->addMonths(6);
                $query->whereBetween('audit_date', [\Carbon\Carbon::now(), $date]);
                break;
        }
    }

    public function scopeCostFilter($query, $min, $max)
    {

        $query->whereBetween('purchased_cost', [$min, $max]);
    }

    public function scopeAssetFilter($query, array $filters)
    {
        $query->when($filters['asset_tag'] ?? false, fn($query, $asset_tag) => $query->where('asset_tag', 'like', '%' . $asset_tag . '%')
            ->orWhere('name', 'like', '%' . $asset_tag . '%')
            ->orWhere('serial_no', 'like', '%' . $asset_tag . '%')
            ->orWhere('order_no', 'like', '%' . $asset_tag . '%'));

    }

    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function depreciation_value()
    {
        $eol = Carbon::parse($this->purchased_date)->addYears($this->depreciation());
        if($eol->isPast())
        {
            return 0;
        } else
        {
            $age = Carbon::now()->floatDiffInYears($this->purchased_date);
            $percent = 100 / $this->model->depreciation->years;
            $percentage = floor($age) * $percent;
            $dep = $this->purchased_cost * ((100 - $percentage) / 100);

            return $dep;
        }

    }

    public function depreciation_value_by_date($date)
    {
        if($this->model()->exists() && $this->model->depreciation()->exists())
        {
            $age = $date->floatDiffInYears($this->purchased_date);
            $percent = 100 / $this->model->depreciation->years;
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

    }

    public function depreciation()
    {
        return $this->model->depreciation->years ?? 0;
    }

    public static function updateCache()
    {
        //The Variables holding the total of Assets available to the User
        $assets_total = 0;
        $cost_total = 0;
        $audits_due = 0;
        $audits_overdue = 0;
        $dep_total = 0;
        $deploy_assets = 0;

        foreach(Location::all() as $location)
        {
            $loc_cost_total = 0;
            $loc_audits_due = 0;
            $loc_audits_overdue = 0;
            $loc_dep_total = 0;
            $loc_deploy_assets = 0;
            $id = $location->id;

            $assets = Asset::whereLocationId($location->id)
                ->with('model', 'status')
                ->select('asset_model', 'purchased_cost', 'purchased_date', 'status_id', 'audit_date')
                ->get()
                ->map(function($item, $key) {
                    $item['depreciation_value'] = $item->depreciation_value();
                    $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;

                    return $item;
                });

            //Get the Total Amount of Assets available for this location and set it in Cache
            $loc_total = $assets->count();
            Cache::rememberForever("assets-L{$id}-total", function() use ($loc_total) {
                return $loc_total;
            });

            //Add the total to the Total amount of Assets
            $assets_total += $loc_total;

            foreach($assets as $asset)
            {
                $loc_cost_total += $asset->purchased_cost;
                $loc_dep_total += $asset->depreciation_value;
                $loc_audit_date = \Carbon\Carbon::parse($asset->audit_date);
                $now = \Carbon\Carbon::now();
                if($loc_audit_date->isPast())
                {
                    $loc_audits_overdue++;
                } else if($loc_audit_date->diffInMonths($now) < 3)
                {
                    $loc_audits_due++;
                }
                if($asset->deployable !== 1)
                {
                    $loc_deploy_assets++;
                }


            }

            /* The Cache Values for the Location */
            Cache::set("assets-L{$id}-cost", round($loc_cost_total));
            $cost_total += $loc_cost_total;
            Cache::set("assets-L{$id}-dep", round($loc_dep_total));
            $dep_total += $loc_dep_total;
            Cache::set("assets-L{$id}-deploy", round($loc_deploy_assets));
            $deploy_assets += $loc_deploy_assets;
            Cache::set("assets-L{$id}-due", round($loc_audits_due));
            $audits_due += $loc_audits_due;
            Cache::set("assets-L{$id}-overdue", round($loc_audits_overdue));
            $audits_overdue += $loc_audits_overdue;
        }
    }

    public static function updateLocationCache(Location $location)
    {
        $loc_cost_total = 0;
        $loc_audits_due = 0;
        $loc_audits_overdue = 0;
        $loc_dep_total = 0;
        $loc_deploy_assets = 0;
        $id = $location->id;

        $assets = Asset::whereLocationId($location->id)
            ->with('model', 'status')
            ->select('asset_model', 'purchased_cost', 'purchased_date', 'status_id', 'audit_date')
            ->get()
            ->map(function($item, $key) {
                $item['depreciation_value'] = $item->depreciation_value();
                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;

                return $item;
            });

        //Get the Total Amount of Assets available for this location and set it in Cache
        $loc_total = $assets->count();
        Cache::rememberForever("assets-L{$id}-total", function() use ($loc_total) {
            return $loc_total;
        });

        foreach($assets as $asset)
        {
            $loc_cost_total += $asset->purchased_cost;
            $loc_dep_total += $asset->depreciation_value;
            $loc_audit_date = \Carbon\Carbon::parse($asset->audit_date);
            $now = \Carbon\Carbon::now();
            if($loc_audit_date->isPast())
            {
                $loc_audits_overdue++;
            } else if($loc_audit_date->diffInMonths($now) < 3)
            {
                $loc_audits_due++;
            }
            if($asset->deployable !== 1)
            {
                $loc_deploy_assets++;
            }


        }

        /* The Cache Values for the Location */
        Cache::set("assets-L{$id}-cost", round($loc_cost_total));
        Cache::set("assets-L{$id}-dep", round($loc_dep_total));
        Cache::set("assets-L{$id}-deploy", round($loc_deploy_assets));
        Cache::set("assets-L{$id}-due", round($loc_audits_due));
        Cache::set("assets-L{$id}-overdue", round($loc_audits_overdue));
    }

    public static function getCache($ids)
    {
        $assets_total = 0;
        $cost_total = 0;
        $audits_due = 0;
        $audits_overdue = 0;
        $dep_total = 0;
        $deploy_assets = 0;

        $locations = Location::find($ids);

        foreach($locations as $location)
        {
            $id = $location->id;
            /* The Cache Values for the Location */
            if(! Cache::has("assets-L{$id}-total") &&
                ! Cache::has("assets-L{$id}-cost") &&
                ! Cache::has("assets-L{$id}-dep") &&
                ! Cache::has("assets-L{$id}-deploy") &&
                ! Cache::has("assets-L{$id}-due") &&
                ! Cache::has("assets-L{$id}-overdue")
            )
            {
                Asset::updateLocationCache($location);
            }

            $assets_total += Cache::get("assets-L{$id}-total");
            $cost_total += Cache::get("assets-L{$id}-cost");
            $dep_total += Cache::get("assets-L{$id}-dep");
            $deploy_assets += Cache::get("assets-L{$id}-deploy");
            $audits_due += Cache::get("assets-L{$id}-due");
            $audits_overdue += Cache::get("assets-L{$id}-overdue");
        }

        //Totals of the Assets
        Cache::rememberForever('assets_total', function() use ($assets_total) {
            return round($assets_total);
        });

        Cache::rememberForever('assets_cost', function() use ($cost_total) {
            return round($cost_total);
        });

        Cache::rememberForever('assets_dep', function() use ($dep_total) {
            return round($dep_total);
        });

        Cache::rememberForever('assets_deploy', function() use ($deploy_assets) {
            return round($deploy_assets);
        });

        Cache::rememberForever('audits_due', function() use ($audits_due) {
            return round($audits_due);
        });

        Cache::rememberForever('audits_overdue', function() use ($audits_overdue) {
            return round($audits_overdue);
        });
    }

    public static function expenditure($year, $locations)
    {
        $expenditure = 0;
        $assets = Asset::whereIn('location_id', $locations)->whereYear('purchased_date', $year)->select('donated', 'purchased_cost', 'location_id')->get();
        foreach($assets as $asset)
        {
            if($asset->donated !== 1)
            {
                $expenditure += $asset->purchased_cost;
            }
        }

        return $expenditure;
    }

    public static function donations($year, $locations)
    {
        $donations = 0;
        $assets = Asset::whereIn('location_id', $locations)->whereYear('purchased_date', $year)->select('donated', 'purchased_cost', 'location_id')->get();
        foreach($assets as $asset)
        {
            if($asset->donated === 1)
            {
                $donations += $asset->purchased_cost;
            }
        }

        return $donations;

    }

}
