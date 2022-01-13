<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Transfer extends Model
{

    use HasFactory;

    protected $fillable = ['model_type', 'model_id', 'location_from', 'location_to', 'value', 'user_id', 'super_id', 'notes', 'date', 'created_at', 'value', 'old_tag', 'new_tag'];

    public function from()
    {
        return $this->belongsTo(Location::class, 'location_from')->with('photo');
    }

    public function to()
    {
        return $this->belongsTo(Location::class, 'location_to')->with('photo');
    }

    public function requested()
    {
        return $this->belongsTo(User::class, 'user_id')->with('photo');
    }

    public function approved()
    {
        return $this->belongsTo(User::class, 'super_id')->with('photo');
    }

    public static function updateCache(){
        Cache::flush('transfer_count');
        Cache::set('transfers_count', Transfer::all()->count());
    }


}
