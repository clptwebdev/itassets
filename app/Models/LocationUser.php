<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;
use App\Models\Location;
use App\Models\Log;
use Carbon\Carbon;

class LocationUser extends Pivot {

    use HasFactory;

    protected $table = "location_user";

    public static function boot()
    {
        parent::boot();

        static::created(function($pivot) {
            $user = User::find($pivot->user_id);
            $location = Location::find($pivot->location_id);
            Log::create([
                'user_id' => auth()->user()->id ?? 'No User',
                'loggable_type' => 'App\Models\User',
                'loggable_id' => $user->id,
                'data' => auth()->user()->name . ' granted ' . $user->name . ' with permissions for ' . $location->name,
            ]);
        });

        static::deleted(function($pivot) {
            $user = User::find($pivot->user_id);
            $location = Location::find($pivot->location_id);
            Log::create([
                'user_id' => auth()->user()->id ?? 'No User',
                'log_type' => 'App\Models\User',
                'log_id' => $user->id,
                'data' => auth()->user()->name . ' removed ' . $user->name . ' with permissions for ' . $location->name,
            ]);
        });
    }

}
