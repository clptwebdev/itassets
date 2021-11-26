<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Asset;
use App\Models\Location;

class checkAssetTag implements Rule
{
    //The Location Id to see if the Asset Tag Exists
    protected $location;

    public function __construct($location)
    {

        if(is_int($location)){
            $this->location = $location;
        }else{
            $locations = Location::whereName($location)->first();
        dd($locations);
            $this->locations = $locations->id;
        }
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
        //If the Asset Tag exists at the Location returns False
        if($asset = Asset::where(["asset_tag" => $value, "location_id" => $this->location])->first()){
            return false;
        }else{
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The Asset Tag is already assigned in this location';
    }
}
