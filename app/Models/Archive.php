<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Archive extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'asset_tag', 'asset_model', 'serial_no', 'status_id', 'purchased_date', 'purchased_cost', 'archived_cost', 'supplier_id', 'order_no', 'location_id', 'user_id', 'super_id', 'notes', 'date'];

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function requested()
    {
        return $this->belongsTo(User::class, 'user_id')->with('photo');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'super_id')->with('photo');
    }

}
