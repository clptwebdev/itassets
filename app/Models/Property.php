<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location_id', 'value', 'depreciation', 'type', 'date'];

    public function location()
    {
        return $this->belongsTo(Location::class)->with('photo');
    }

    public function scopeLocationFilter($query, $locations)
    {
        return $query->whereIn('location_id', $locations);
    }
}
