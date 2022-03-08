<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model {

    use HasFactory;

    protected $fillable = ['name', 'type', 'format', 'required', 'value', 'help'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function fieldsets()
    {
        return $this->belongsToMany(Fieldset::class);
    }

    public function getTypeAttribute($type)
    {
        return ucfirst($type);
    }

    public function getFormatAttribute($format)
    {
        return ucfirst($format);
    }

    public function assets()
    {
        return $this->belongsToMany(Asset::class)->withPivot('value');
    }

}
