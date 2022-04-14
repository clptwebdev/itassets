<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Archive extends Model {

    use HasFactory;

    protected $guarded = [];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function notes(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
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

    public function requested()
    {
        return $this->belongsTo(User::class, 'user_id')->with('photo');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'super_id')->with('photo');
    }

    public function created_user()
    {
        return $this->belongsTo(User::class, 'created_user')->with('photo');
    }

    public static function updateCache()
    {
        Cache::forget('archive_count');
        Cache::set('archive_count', Archive::all()->count());
    }

    //Works out the depreciation value at the date that is passed through to the function
    //Use the Depreciation time to minus the depreication charge
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

}
