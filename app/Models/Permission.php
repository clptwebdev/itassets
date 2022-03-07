<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    protected $guarded = [];

    use HasFactory;

    public function roles()
    {
        return $this->hasOne(Role::class);
    }

    public function model(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => ucfirst($value),
        );
    }

}
