<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type', 'format', 'value', 'help'];

    public function fieldsets()
    {
        return $this->belongsToMany(Fieldset::class);
    }

    public function getTypeAttribute($type){
        return ucfirst($type);
    }

    public function getFormatAttribute($format){
        return ucfirst($format);
    }

    public function assets(){
        return $this->belongsToMany(Asset::class)->withPivot('value');
    }
}
