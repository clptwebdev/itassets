<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model {

    use HasFactory;

    protected $fillable = ['name'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function assets()
    {
        return $this->morphedByMany(Asset::class, 'cattable');
    }

    public function components()
    {
        return $this->morphedByMany(Component::class, 'cattable');
    }

    public function accessories()
    {
        return $this->morphedByMany(Accessory::class, 'cattable');
    }

    public function consumables()
    {
        return $this->morphedByMany(Consumable::class, 'cattable');
    }

    public function miscellanea()
    {
        return $this->morphedByMany(Miscellanea::class, 'cattable');
    }

    public function ffe()
    {
        return $this->morphedByMany(FFE::class, 'cattable');
    }

}
