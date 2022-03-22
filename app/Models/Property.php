<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model {

    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'location_id', 'purchased_cost', 'depreciation', 'type', 'purchased_date'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    //Returns the Location attached to the property
    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    //Works out the depreciation value at the date that is passed through to the function
    //Use the Depreciation time to minus the depreication charge
    public function depreciation_value($date)
    {
        $age = $date->floatDiffInYears($this->purchased_date);
        $percent = 100 / $this->depreciation;
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

    //Gets the building type in the table and displays it as a string
    // (1 = Freehold Land 2 = Freehold Buildings 3 = Leadsehold Land 4 = Leasehold Buildings)
    public function getType()
    {
        switch($this->type)
        {
            case 1:
                return "Freehold Land";
                break;
            case 2:
                return "Freehold Buildings";
                break;
            case 3:
                return "Leasehold Land";
                break;
            case 4:
                return "Leasehold Buildings";
                break;
            default:
                return "Unknown";
        }
    }

    /////////////////////////////////////////////////
    ///////////////////Filters///////////////////////
    /////////////////////////////////////////////////

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

    public function comment()
    {
        return $this->morphToMany(Comment::class, "commentables");
    }

}
