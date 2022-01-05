<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Location extends Model
{
//    public $with=['asset','accessory','components','consumable','miscellanea','photo'];
    use HasFactory;
    protected $fillable = ['name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'telephone', 'email', 'photo_id', 'icon'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function asset(){
        return $this->hasMany(Asset::class);
    }

    public function assets(){
        return $this->hasMany(Asset::class);
    }

    public function miscellanea()
    {
        return $this->hasMany(Miscellanea::class);
    }

    public function components(){
        return $this->hasMany(Component::class);
    }

    public function accessory(){
        return $this->hasMany(Accessory::class);
    }

    public function accessories(){
        return $this->hasMany(Accessory::class);
    }

    public function consumable(){
        return $this->hasMany(Consumable::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)
            ->using(LocationUser::class);
    }

    public function expenditure($year){
        $expenditure = 0;
        $assets = $this->assets()->whereYear('purchased_date', $year)->select('donated', 'purchase_cost');
        foreach($assets as $asset){
            if($asset->donated != 1){
                $expenditure += $asset->purchased_cost;
            }
        }
        return dd($expenditure);
        
    }
}
