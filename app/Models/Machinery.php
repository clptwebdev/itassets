<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Machinery extends Model {

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

    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function depreciation_value_by_date($date)
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

    public function depreciation_value()
    {
        $eol = \Carbon\Carbon::parse($this->purchased_date)->addYears($this->depreciation);
        if($eol->isPast())
        {
            return 0;
        } else
        {
            $age = Carbon::now()->floatDiffInYears($this->purchased_date);
            $percent = 100 / $this->depreciation;
            $percentage = floor($age) * $percent;
            $dep = $this->purchased_cost * ((100 - $percentage) / 100);

            return $dep;
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

    public function scopeSearchFilter($query, $search)
    {
        return $query->where('machineries.name', 'LIKE', "%{$search}%")
            ->orWhere('machineries.registration', 'LIKE', "%{$search}%");
    }

    public function scopeCategoryFilter($query, $category)
    {
        $pivot = $this->category()->getTable();

        $query->whereHas('category', function($q) use ($category, $pivot) {
            $q->whereIn("{$pivot}.category_id", $category);
        });
    }

}
