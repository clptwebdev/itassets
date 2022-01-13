<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\Cache;

class Miscellanea extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name', 'serial_no', 'purchased_date', 'purchased_cost', 'donated', 'supplier_id','status_id', 'order_no', 'warranty', 'location_id', 'room', 'notes','manufacturer_id', 'photo_id', 'depreciation_id'
    ];
//    protected $with =['supplier','location','manufacturer','photo','Status'];


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

    public function depreciation()
    {
        return $this->belongsTo(Depreciation::class);
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

    public function scopeStatusFilter($query, $status){
        return $query->whereIn('status_id', $status);
    }

    public function scopeCategoryFilter($query, $category){
        $pivot = $this->category()->getTable();

        $query->whereHas('category', function ($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        });
    }

    public function scopeCostFilter($query, $amount){
        $amount = str_replace('£', '', $amount);
        $amount = explode(' - ', $amount);
        $query->whereBetween('purchased_cost', [intval($amount[0]), intval($amount[1])]);
    }

    public function scopePurchaseFilter($query, $start, $end){
        $query->whereBetween('purchased_date', [$start, $end]);
    }
    public function scopeSearchFilter($query, $search){
        return $query->where('miscellaneas.name', 'LIKE', "%{$search}%")
            ->orWhere('miscellaneas.serial_no', 'LIKE', "%{$search}%");
    }

    public static function updateCache(){

        //The Variables holding the total of Accessories available to the User
        $miscellaneous_total = 0;
        $miscellaneous_cost_total = 0;
        $miscellaneous_deployed_total = 0;

        foreach(Location::all() as $location){
            
            $id = $location->id;
            //Variables to Hold the Accessories for that Location
            $misc_loc_total = 0;
            $misc_cost = 0;
            $misc_deployed = 0;

            $miscellaneous = Miscellanea::whereLocationId($id)
                            ->select('purchased_cost', 'status_id')
                            ->get() 
                            ->map(function($item, $key) {
                                $item->status()->exists() ? $item['deployable'] = $item->status->deployable : $item['deployable'] = 0;
                                return $item;
                            });

            $misc_loc_total = $miscellaneous->count();
            Cache::rememberForever("misc-L{$id}-total", function () use($misc_loc_total){
                return $misc_loc_total;
            });

            $miscellaneous_total += $misc_loc_total;

            foreach($miscellaneous as $miscellanea){
                $misc_cost += $miscellanea->purchased_cost;
                if($miscellanea->deployable != 1){ $misc_deployed++;}
            }

            Cache::set("misc-L{$id}-cost", round($misc_cost));
            $miscellaneous_cost_total += $misc_cost;
            Cache::set("misc-L{$id}-deploy", round($misc_deployed));
            $miscellaneous_deployed_total += $misc_deployed;
        }

        Cache::rememberForever('miscellaneous_total', function() use($miscellaneous_total){
            return round($miscellaneous_total);
        });

        Cache::rememberForever('miscellaneous_cost', function() use($miscellaneous_cost_total){
            return round($miscellaneous_cost_total);
        });

        Cache::rememberForever('miscellaneous_deploy', function() use($miscellaneous_deployed_total){
            return round($miscellaneous_deployed_total);
        });
    }
}
