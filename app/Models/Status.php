<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model {

    use HasFactory;

    protected $table = 'status';
    protected $fillable = ['name', 'deployable', 'icon', 'colour'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    public function miscellanea()
    {
        return $this->hasMany(Miscellanea::class);
    }

    public function accessory()
    {
        return $this->hasMany(Accessory::class);
    }

    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }

    public function consumable()
    {
        return $this->hasMany(Consumable::class);
    }

    public function ffe()
    {
        return $this->hasMany(FFE::class);
    }

}
