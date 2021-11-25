<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Asset;
use App\Models\Location;

class checkAssetTag implements Rule
{
    
    protected $location;

    public function __construct($location)
    {
        $this->location = $location;
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
        if($asset = Asset::where(["asset_tag" => $value, "location_id" => $this->location])->first()){
            return true;
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
        return 'The Asset Tag is already assigned in this location';
    }
}
