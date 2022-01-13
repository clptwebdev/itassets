<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Cache;

class Requests extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'model_type', 'model_id', 'location_from', 'location_to', 'notes', 'user_id', 'super_id', 'date', 'created_at', 'status', 'new_tag', 'old_tag'];

    public static function updateCache(){
        Cache::flush('request_count');
        Cache::set('request_count', Requests::all()->count());
    }
}
