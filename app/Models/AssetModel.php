<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetModel extends Model {

    use HasFactory;

    protected $fillable = ['name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'];

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

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function asset()
    {
        return $this->hasOne(Model::class, 'id', 'asset_model');
    }

    public function assets()
    {
        return $this->hasMany(Asset::class, 'asset_model');
    }

    public function depreciation()
    {
        return $this->belongsTo(Depreciation::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function fieldset()
    {
        return $this->belongsTo(Fieldset::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }

}

