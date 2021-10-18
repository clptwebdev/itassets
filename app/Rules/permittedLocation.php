<?php

namespace App\Rules;

use App\Models\Location;
use Illuminate\Contracts\Validation\Rule;

class permittedLocation implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($location = Location::where(["name" => $value])->first()){
            if(in_array($location->id, auth()->user()->locations->pluck('id')->toArray())){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'You do not have permission to upload to this Location';
    }
}
