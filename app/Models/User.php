<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class User extends Authenticatable {

    use HasFactory, Notifiable;
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role_id', 'location_id', 'photo_id', 'telephone'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['email_verified_at' => 'datetime',];


    public function photo()
    {
        return $this->belongsTo('App\Models\Photo');
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function asset()
    {
        return $this->belongsToMany(Asset::class);
    }

    public function locations(){
        return $this->belongsToMany(Location::class)
            ->using(LocationUser::class);
    }


    public function location_assets(){
        return $this->hasManyDeep(Asset::class, ['location_user', Location::class]);
    }

    public function logs(){
        return $this->morphMany(Log::class, 'loggable');
    }

    public function activity()
    {
        return $this->hasMany(Log::class);
    }

}
