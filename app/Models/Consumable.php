<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consumable extends Model
{
    protected $fillable = ['name', 'serial_no', 'purchased_date', 'purchased_cost', 'supplier_id','status_id', 'order_no', 'warranty', 'location_id', 'notes','manufacturer_id'];

    use HasFactory;

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photoId');
    }


    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    } public function status()
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

}
