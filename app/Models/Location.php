<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'telephone', 'email', 'photo_id', 'icon'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
    public function asset(){
        return $this->hasMany(Asset::class);
    }
}
