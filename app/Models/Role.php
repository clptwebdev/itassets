<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $guarded = [];
    use HasFactory;

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords(str_replace('_', ' ', $value)),
            fn($value) => strtolower($value),
        );
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->hasMany(Permission::class);
    }

    public static function significance(User $user)
    {
        return Role::Where('significance', '<=', $user->role->significance)->get();
    }

}
