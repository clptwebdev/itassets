<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Component extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'serial_no', 'purchased_date', 'purchased_cost', 'supplier_id','status_id', 'order_no', 'warranty', 'location_id', 'notes','manufacturer_id', 'photo_id'];

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

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
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
}
