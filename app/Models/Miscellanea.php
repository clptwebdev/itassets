<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
