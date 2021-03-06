<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model {

    use HasFactory;

    protected $fillable = ["name", "supportUrl", "supportPhone", "supportEmail", "photoId"];//what can  be bulk assigned in tinker

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function supportEmail(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photoId');
    }

    public function miscellanea()
    {
        return $this->hasMany(Miscellanea::class);
    }

    public function assetModel()
    {
        return $this->hasMany(AssetModel::class, 'manufacturer_id');
    }

    public function accessory()
    {
        return $this->hasMany(Accessory::class);
    }

    public function consumable()
    {
        return $this->hasMany(Consumable::class);
    }

    public function component()
    {
        return $this->hasMany(Component::class);
    }

    public function scopeManufacturerFilter($query, $search)
    {

        return $query->where('manufacturers.name', 'LIKE', "%{$search}%")->orWhere('manufacturers.supportUrl', 'LIKE', "%{$search}%");


    }

}
