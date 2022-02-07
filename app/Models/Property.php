<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'location_id', 'value', 'depreciation', 'type', 'date'];

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }

    public function depreciation_value($date)
    {
        $age = $date->floatDiffInYears($this->date);
        $percent = 100 / $this->depreciation;
        $percentage = floor($age)*$percent;
        $value = $this->value * ((100 - $percentage) / 100);

        return $value;
    }

    public function getType(){
        switch($this->type){
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

}
