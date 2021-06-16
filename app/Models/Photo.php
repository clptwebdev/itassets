<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'path'];

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class);
    }

    public function manufacturer()
    {
        return $this->hasOne(Manufacturer::class);
    }

    public function assetModel()
    {
        return $this->hasOne(AssetModel::class);
    }

    public function setPathAttribute($value)
    {
        $this->attributes['path'] = 'storage/'.$value;
    }

}
