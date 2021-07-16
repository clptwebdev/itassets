<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'years'];

    public function models(){
        return $this->hasMany(AssetModel::class);
    }
}
