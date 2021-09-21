<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'telephone', 'email', 'photo_id', 'icon'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function asset(){
        return $this->hasMany(Asset::class);
    }
    
    public function miscellanea()
    {
        return $this->hasMany(Miscellanea::class);
    }

    public function component(){
        return $this->hasMany(Component::class);
    }

    public function accessory(){
        return $this->hasMany(Accessory::class);
    }

    public function consumable(){
        return $this->hasMany(Consumable::class);
    }

    public function users(){
        return $this->belongsToMany(User::class)
            ->using(LocationUser::class);
    }
}
