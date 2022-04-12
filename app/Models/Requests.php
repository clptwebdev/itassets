<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Cache;

class Requests extends Model {

    use HasFactory;

    protected $fillable = ['type', 'model_type', 'model_id', 'location_from', 'location_to', 'notes', 'user_id', 'super_id', 'date', 'created_at', 'status', 'new_tag', 'old_tag'];

    public function notes(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

    public static function updateCache()
    {
        Cache::forget('request_count');
        Cache::set('request_count', Requests::all()->count());
    }

    public function scopeManagerFilter($query, $users)
    {
        return $query->whereIn('user_id', $users);
    }

}
