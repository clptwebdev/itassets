<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetModel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'manufacturer_id', 'model_no', 'depreciation_id', 'eol', 'fieldset_id', 'notes', 'photo_id'];

    public function photo(){
        return $this->belongsTo(Photo::class);
    }

    public function asset(){
        return $this->hasOne(Model::class, 'id', 'asset_model');
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id', 'id');
    }
}
