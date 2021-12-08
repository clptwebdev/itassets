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

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function asset()
    {
        return $this->belongsToMany(Asset::class);
    }

    public function locations(){
        return $this->belongsToMany(Location::class)
            ->using(LocationUser::class);
    }

    //Permissions
    public function location_assets(){
        return $this->hasManyDeep(Asset::class, ['location_user', Location::class]);
    }

    public function location_components(){
        return $this->hasManyDeep(Component::class, ['location_user', Location::class]);
    }

    public function location_accessories(){
        return $this->hasManyDeep(Accessory::class, ['location_user', Location::class]);
    }

    public function location_consumables(){
        return $this->hasManyDeep(Consumable::class, ['location_user', Location::class]);
    }
    public function location_miscellaneous(){
        return $this->hasManyDeep(Miscellanea::class, ['location_user', Location::class]);
    }

    public function logs(){
        return $this->morphMany(Log::class, 'loggable');
    }

    public function activity()
    {
        return $this->hasMany(Log::class);
    }

    public function random_password($length)
    {
        //A list of characters that can be used in our
        //random password.
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-.[]?*()';
        //Create a blank string.
        $password = '';
        //Get the index of the last character in our $characters string.
        $characterListLength = mb_strlen($characters, '8bit') - 1;
        //Loop from 1 to the $length that was specified.
        foreach(range(1, $length) as $i){
            $password .= $characters[random_int(0, $characterListLength)];
        }
        return $password;

    }

}
