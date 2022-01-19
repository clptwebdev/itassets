<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Location;

class checkAssetTag implements Rule
{
    //The Location Id to see if the Asset Tag Exists
    protected $location;

    public function __construct($location)
    {
        if(is_int(intval($location))){
            $this->location = intval($location);
        }else{
            $locations = Location::whereName($location)->first();

            $this->location = $locations->id;
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

        if($asset = Asset::where("asset_tag", $value)->where("location_id", '=', $this->location)->first() || $accessory = Accessory::where("asset_tag", $value)->where("location_id", '=', $this->location)->first()){
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
