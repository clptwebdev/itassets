<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fieldset extends Model {

    use HasFactory;

    protected $fillable = ['name'];

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public function fields()
    {
        return $this->belongsToMany(Field::class);
    }

    public function models()
    {
        return $this->hasMany(AssetModel::class);
    }

}
