<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Cache;

class Consumable extends Model
{
    protected $fillable = [
        'name', 'serial_no', 'purchased_date', 'purchased_cost', 'supplier_id','status_id', 'order_no', 'warranty', 'location_id', 'notes','manufacturer_id', 'photo_id'
    ];

    use HasFactory;
    use SoftDeletes;

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photo_id');
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

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }
    public function comment()
    {
        return $this->morphToMany(Comment::class, 'commentables');
    }

    public function category(){
        return $this->morphToMany(Category::class, 'cattable');
    }

    public function logs(){
        return $this->morphMany(Log::class, 'loggable');
    }

    public function scopeLocationFilter($query, $locations){
        return $query->whereIn('location_id', $locations);
    }

    public function scopeCategoryFilter($query, $category){
        $pivot = $this->category()->getTable();

        $query->whereHas('category', function ($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        });
    }
    
    public function scopeStatusFilter($query, $status){
        return $query->whereIn('status_id', $status);
    }

    public static function updateCache(){

        //The Variables holding the total of Accessories available to the User
        $consumables_total = 0;
        $consumables_cost_total = 0;
        $consumables_deployed_total = 0;

        foreach(Location::all() as $location){
            $id = $location->id;
            //Variables to Hold the Accessories for that Location
            $consumables_loc_total = 0;
            $consumables_cost = 0;
            $consumables_deployed = 0;

            $consumables = Consumable::whereLocationId($id)
                            ->select('purchased_cost', 'status_id')
                            ->get() 
                            ->map(function($item, $key) {
                                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                return $item;
                            });

            $consumables_loc_total = $consumables->count();
            Cache::rememberForever("consumables-L{$id}-total", function () use($consumables_loc_total){
                return $consumables_loc_total;
            });

            $consumables_total += $consumables_loc_total;

            foreach($consumables as $consumable){
                $consumables_cost += $consumables->purchased_cost;
                if($consumable->deployable != 1){ $consumables_deployed++;}
            }

            Cache::set("consumables-L{$id}-cost", round($consumables_cost));
            $consumables_cost_total += $consumables_cost;
            Cache::set("consumables-L{$id}-deploy", round($consumables_deployed));
            $consumables_deployed_total += $consumables_deployed;
        }

        Cache::rememberForever('consumables_total', function() use($consumables_total){
            return round($consumables_total);
        });

        Cache::rememberForever('consumables_cost', function() use($consumables_cost_total){
            return round($consumables_cost_total);
        });

        Cache::rememberForever('consumables_deploy', function() use($consumables_deployed_total){
            return round($consumables_deployed_total);
        });
    }

    public static function updateLocationCache(Location $location){ 
        $id = $location->id;

        //Variables to Hold the Accessories for that Location
        $consumables_cost = 0;
        $consumables_deployed = 0;

        $consumables = Consumable::whereLocationId($id)
        ->select('purchased_cost', 'status_id')
        ->get()
        ->map(function($item, $key) {
            $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
            return $item;
        });

        $consumables_loc_total = $consumables->count();
        Cache::rememberForever("consumables-L{$id}-total", function () use($consumables_loc_total){
            return $consumables_loc_total;
        });

        foreach($consumables as $consumable){
            $consumables_cost += $consumable->purchased_cost;
            if($consumable->deployable != 1){ $consumables_deployed++;}
        }

        Cache::set("consumables-L{$id}-cost", round($consumables_cost));
        Cache::set("consumables-L{$id}-deploy", round($consumables_deployed));
    }

    public static function getCache($ids){
         //The Variables holding the total of Accessories available to the User
         $consumables_total = 0;
         $consumables_cost_total = 0;
         $consumables_deployed_total = 0;
 

        $locations = Location::find($ids);

        foreach($locations as $location){
            $id = $location->id;
            /* The Cache Values for the Location */
            if( !Cache::has("consumables-L{$id}-total") && 
                !Cache::has("consumables-L{$id}-cost") &&
                !Cache::has("consumables-L{$id}-deploy")
            ){
                Consumable::updateLocationCache($location);
            }

            $consumables_total += Cache::get("consumables-L{$id}-total");
            $consumables_cost_total += Cache::get("consumables-L{$id}-cost");
            $consumables_deployed_total += Cache::get("consumables-L{$id}-deploy");
        }

         /* consumables Calcualtions */
            
         Cache::rememberForever('consumables_total', function() use($consumables_total){
            return round($consumables_total);
        });

        Cache::rememberForever('consumables_cost', function() use($consumables_cost_total){
            return round($consumables_cost_total);
        });

        Cache::rememberForever('consumables_deploy', function() use($consumables_deployed_total){
            return round($consumables_deployed_total);
        });
    }
}
