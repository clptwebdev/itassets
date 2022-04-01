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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }
    //Filters the properties that are based in the selected locations
    //$locations is an array of the location ids
    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    //checks renewal date
    public function isExpired()
    {
        if(Carbon::parse($this->renewal_date)->isPast())
        {
            return true;
        } else
        {
            return false;
        }
    }

}
