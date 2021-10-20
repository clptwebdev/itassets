<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $table = 'status';
    protected $fillable = ['name', 'deployable', 'icon', 'colour'];

    public function assets(){
        return $this->hasMany(Asset::class);
    }
    public function component(){
        return $this->hasMany(Component::class);
    }
    public function miscellanea()
    {
        return $this->hasMany(Miscellanea::class);
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
}
