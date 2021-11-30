<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function assets(){
        return $this->hasMany(Asset::class, 'asset_model');
    }

    public function depreciation(){
        return $this->belongsTo(Depreciation::class);
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class);
    }

    public function fieldset(){
        return $this->belongsTo(Fieldset::class);
    }

    public function component(){
        return $this->belongsTo(Component::class);
    }

    //works out current assets value
    public function depreciation_value()
    {
        return 1;
//        if($this->model()->exists() && $this->model->depreciation()->exists()){
//            $eol = Carbon::parse($this->purchased_date)->addYears($this->model->depreciation->years);
//            if($eol->isPast()){
//                return 0;
//            }else{
//                $age = Carbon::now()->floatDiffInYears($this->purchased_date);
//                $percent = 100 / $this->model->depreciation->years;
//                $percentage = floor($age)*$percent;
//                $dep = $this->purchased_cost * ((100 - $percentage) / 100);
//                return $dep;
//            }
//        }else
//        {
//            return $this->purchased_cost;
//        }
    }}

