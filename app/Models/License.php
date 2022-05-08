<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class License extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
            fn($value) => strtolower($value),
        );
    }

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }
    //Filters the properties that are based in the selected locations
    //Filters out the property by the date acquired/purchased. Start = the Start Date End = the End Date
    public function scopePurchaseFilter($query, $start, $end)
    {
        $query->whereBetween('purchased_date', [$start, $end]);
    }

    //Filters the porperty thats value is between two values set in one string
    //These variables are passed from the sldier on the filter
    public function scopeCostFilter($query, $min, $max)
    {
        $query->whereBetween('purchased_cost', [$min, $max]);
    }

    //Filters the properties that are based in the selected locations
    //$locations is an array of the location ids
    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function scopeSearchFilter($query, $search)
    {
        return $query->where('licenses.name', 'LIKE', "%{$search}%");
    }

    //checks renewal date
    public function isExpired()
    {
        if(Carbon::parse($this->expiry)->isPast())
        {
            return true;
        } else
        {
            return false;
        }
    }

}
