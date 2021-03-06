<?php

namespace App\Models;

use App\Http\Controllers\SoftwareController;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Location extends Model {

//    public $with=['asset','accessory','components','consumable','miscellanea','photo'];
    use HasFactory;

    protected $fillable = ['name', 'address_1', 'address_2', 'city', 'county', 'postcode', 'telephone', 'email', 'photo_id', 'icon'];

    public function email(): Attribute
    {
        return new Attribute(
            fn($value) => strtolower($value),
        );
    }

    public function address_1(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
        );
    }

    public function address_2(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
        );
    }

    public function city(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
        );
    }

    public function county(): Attribute
    {
        return new Attribute(
            fn($value) => ucwords($value),
        );
    }

    public function postcode(): Attribute
    {
        return new Attribute(
            fn($value) => strtoupper($value),
        );
    }

    public function photo()
    {
        return $this->belongsTo(Photo::class);
    }

    public function asset()
    {
        return $this->hasMany(Asset::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function aucs()
    {
        return $this->hasMany(AUC::class);
    }

    public function ffe()
    {
        return $this->hasMany(FFE::class);
    }

    public function miscellanea()
    {
        return $this->hasMany(Miscellanea::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }

    public function accessory()
    {
        return $this->hasMany(Accessory::class);
    }

    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }

    public function consumable()
    {
        return $this->hasMany(Consumable::class);
    }

    public function property()
    {
        return $this->hasMany(Property::class);
    }

    public function software()
    {
        return $this->hasMany(Software::class);
    }

    public function vehicle()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function broadband()
    {
        return $this->hasMany(Broadband::class);
    }

    public function machinery()
    {
        return $this->hasMany(Machinery::class);
    }

    public function license()
    {
        return $this->hasMany(License::class);
    }

    public function auc()
    {
        return $this->hasMany(AUC::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
            ->using(LocationUser::class);
    }

    public function singleBroadband()
    {
        return $this->hasOne(Broadband::class)->latest('renewal_date');

    }

    public function full_address($sep = ', ')
    {
        $output = $this->address_1 . $sep;
        if($this->address_2 != '')
        {
            $output .= $this->address_2 . $sep;
        }
        $output .= $this->city . $sep . $this->postcode;

        return $output;
    }

    public function expenditure($year)
    {
        $expenditure = 0;
        $assets = $this->assets()->whereYear('purchased_date', $year)->select('donated', 'purchased_cost')->get();
        foreach($assets as $asset)
        {
            if($asset->donated !== 1)
            {
                $expenditure += $asset->purchased_cost;
            }
        }

        return $expenditure;

    }

    public function business_expenditure($year)
    {
        $expenditure = 0;
        $assets = $this->assets()->whereYear('purchased_date', $year)->where('donated', '=', 0)->sum('purchased_cost');
        $accessories = $this->accessories()->whereYear('purchased_date', $year)->where('donated', '=', 0)->sum('purchased_cost');
        $property = $this->property()->whereYear('purchased_date', $year)->sum('purchased_cost');
        $auc = $this->auc()->whereYear('purchased_date', $year)->sum('purchased_cost');
        $ffe = $this->ffe()->whereYear('purchased_date', $year)->where('donated', '=', 0)->sum('purchased_cost');
        $machinery = $this->machinery()->whereYear('purchased_date', $year)->where('donated', '=', 0)->sum('purchased_cost');
        $vehicle = $this->vehicle()->whereYear('purchased_date', $year)->where('donated', '=', 0)->sum('purchased_cost');
        $software = $this->software()->whereYear('purchased_date', $year)->where('donated', '=', 0)->sum('purchased_cost');
        
        $total = $assets + $accessories + $property + $auc + $ffe + $machinery + $vehicle +$software;
        $expenditure += $total;


        return $expenditure;

    }

    public function donations($year)
    {
        $donations = 0;
        $assets = $this->assets()->whereYear('purchased_date', $year)->select('donated', 'purchased_cost')->get();
        foreach($assets as $asset)
        {
            if($asset->donated === 1)
            {
                $donations += $asset->purchased_cost;
            }
        }

        return $donations;

    }

    public function depreciation($y)
    {
        $depreciation = 0;
        $year = \Carbon\Carbon::parse($y);
        $assets = $this->assets()->select('asset_model', 'donated', 'purchased_cost', 'purchased_date')->get();
        foreach($assets as $asset)
        {
            if($asset->model()->exists() && $asset->model->depreciation()->exists())
            {
                $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                if($eol->isPast())
                {
                } else
                {
                    $age = $year->floatDiffInYears($asset->purchased_date);
                    $percent = 100 / $asset->model->depreciation->years;
                    $percentage = floor($age) * $percent;
                    $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                    if($dep < 0)
                    {
                        $dep = 0;
                    }
                    $depreciation += $dep;
                }
            } else
            {
                $depreciation += $asset->purchased_cost;
            }
        }

        return round($depreciation);
    }

    public function depreciations()
    {
        $values = [];
        $assets = $this->assets()
            ->leftJoin('asset_models', 'assets.asset_model', '=', 'asset_models.id')
            ->leftJoin('depreciations', 'depreciations.id', '=', 'asset_models.depreciation_id')
            ->select('assets.donated', 'assets.purchased_cost', 'assets.purchased_date', 'depreciations.years')
            ->get();

        foreach($assets as $asset)
        {
            foreach(range(\Carbon\Carbon::now()->year, \Carbon\Carbon::now()->year + 3) as $y)
            {
                $depreciation = 0;
                $year = \Carbon\Carbon::parse('01-01-' . $y);
                if($asset->years != 0)
                {
                    $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->years);
                    if($eol->isPast())
                    {

                    } else
                    {
                        $age = $year->floatDiffInYears($asset->purchased_date);
                        $percent = 100 / $asset->years;
                        $percentage = floor($age) * $percent;
                        $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                        $depreciation += $dep;
                    }
                } else
                {
                    $depreciation += $asset->purchased_cost;
                }

                if($depreciation < 0) $depreciation = 0;

                if(array_key_exists($y, $values))
                {
                    $values[$y] = round($values[$y] + $depreciation);
                } else
                {
                    $values[$y] = round($depreciation);
                }

            }
        }

        return $values;
    }

}
