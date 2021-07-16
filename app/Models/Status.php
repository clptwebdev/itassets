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
}
