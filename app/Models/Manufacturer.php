<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $fillable=["name","supportUrl","supportPhone","supportEmail","photoId"];//what can  be bulk assigned in tinker

    public function photo()
    {
        return $this->belongsTo(Photo::class, 'photoId');
    }

}
