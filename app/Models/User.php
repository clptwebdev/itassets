<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    protected $fillable = ['name', 'email', 'password', 'role_id', 'location_id', 'photo_id', 'telephone', 'address_1', 'address_2', 'city', 'postcode'];

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

    public function name(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => ucfirst($value),
        );
    }

    public function email(): Attribute
    {
        return new Attribute(
            fn($value) => ucfirst($value),
            fn($value) => strtolower($value),
        );
    }

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

    public function locations()
    {
        return $this->belongsToMany(Location::class)
            ->using(LocationUser::class);
    }

    public function manager()
    {
        return $this->hasOne(User::class, 'manager_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    //Permissions
    public function location_assets()
    {
        return $this->hasManyDeep(Asset::class, ['location_user', Location::class]);
    }

    public function location_components()
    {
        return $this->hasManyDeep(Component::class, ['location_user', Location::class]);
    }

    public function location_accessories()
    {
        return $this->hasManyDeep(Accessory::class, ['location_user', Location::class]);
    }

    public function location_consumables()
    {
        return $this->hasManyDeep(Consumable::class, ['location_user', Location::class]);
    }

    public function location_miscellaneous()
    {
        return $this->hasManyDeep(Miscellanea::class, ['location_user', Location::class]);
    }

    public function location_property()
    {
        return $this->hasManyDeep(Property::class, ['location_user', Location::class]);
    }

    public function location_software()
    {
        return $this->hasManyDeep(Software::class, ['location_user', Location::class]);
    }

    public function location_license()
    {
        return $this->hasManyDeep(License::class, ['location_user', Location::class]);
    }

    public function location_auc()
    {
        return $this->hasManyDeep(AUC::class, ['location_user', Location::class]);
    }

    public function location_ffe()
    {
        return $this->hasManyDeep(FFE::class, ['location_user', Location::class]);
    }

    public function location_vehicle()
    {
        return $this->hasManyDeep(Vehicle::class, ['location_user', Location::class]);
    }

    public function locationsArray(): array
    {
        // gets all locations' id attached to a user (used in polices)
        return auth()->user()->locations->pluck('id')->toArray();
    }

    public function logs()
    {
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
        foreach(range(1, $length) as $i)
        {
            $password .= $characters[random_int(0, $characterListLength)];
        }

        return $password;

    }

    public function expiredUser()
    {
        //true or false condition if the user has logged in recently
        $authLogs = Log::whereUserId($this->id)->where('loggable_type', '=', 'auth')->get();

        //Checks the auth logs for this user and see's if there are any for anywhere in the last 3 months if not remove there account.
        if($authLogs->where('created_at', '>=', Carbon::parse(now()->subMonths(3))->format('Y-m-d'))->first())
        {
            return false;
        } else
        {
            return true;
        }


    }

    public function scopeSuperAdmin($query)
    {
        $role = Role::whereName('super-admin')->first();
        if($role)
        {
            return User::whereRoleId($role->id)->get();
        }
    }

    public static function SuperAdmin()
    {
        $role = Role::whereName('global_admin')->first();
        if($role)
        {
            return User::whereRoleId($role->id)->get();
        }
    }

    public static function itManager()
    {
        $role = Role::whereName('it_manager')->first();
        if($role)
        {
            return User::whereRoleId($role->id)->get();
        }
    }

    public static function globalAdmins()
    {
        $role = Role::whereName('global_admin')->first();
        if($role)
        {
            return User::whereRoleId($role->id)->get();
        }
    }

    public function isBusiness()
    {

        return $this->role->name == 'Business Manager';
    }

    public function isGlobal()
    {

        return $this->role->name == 'Global Admin';
    }

}
