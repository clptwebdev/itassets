<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'telephone', 'fax', 'email', 'url', 'photo_id', 'notes'];

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }
    public function asset(){
        return $this->hasOne(Asset::class);

    }

}
