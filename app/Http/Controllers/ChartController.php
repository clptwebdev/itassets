<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Location;
use Illuminate\Http\Request;
use PDF;

class ChartController extends Controller {

    //
    public function getPieChart()
    {

        $locs = auth()->user()->locations;
        $assets = auth()->user()->location_assets()->count();
        $data = array();

        foreach($locs as $location)
        {
            $row['name'] = $location->name;
            $row['icon'] = $location->icon;
            $row['asset'] = count($location->asset);
            $data[] = $row;
        }

        return json_encode($data);
    }

    public function getAssetValueChart()
    {

        $locations = auth()->user()->locations;

        $data = array();
        foreach($locations as $location)
        {
            $yearValues = array();
            for($i = 0; $i < 4; $i++)
            {
                $y = \Carbon\Carbon::now()->addYears($i);
                $yv = 0;
                foreach($location->asset as $asset)
                {
                    if($asset->model()->exists() && $asset->model->depreciation()->exists())
                    {
                        $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                        if($eol->isPast())
                        {
                        } else
                        {
                            $age = $y->floatDiffInYears($asset->purchased_date);
                            $percent = 100 / $asset->model->depreciation->years;
                            $percentage = floor($age) * $percent;
                            $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                            if($dep < 0)
                            {
                                $dep = 0;
                            }
                            $yv += $dep;
                        }
                    } else
                    {
                        $yv += $asset->purchased_cost;
                    }
                }
                $yearValues[$y->year] = round($yv);
                unset($age);
                unset($percentage);
                unset($dep);
            }
            $data[] = ['name' => $location->name, 'icon' => $location->icon, 'years' => $yearValues];
        }

        return json_encode($data);
    }

    public function getAssetAuditChart()
    {
        $data = array();

        $locs = auth()->user()->locations;

        foreach($locs as $locations)
        {
            $past = 0;
            $month = 0;
            $quarter = 0;
            $half = 0;
            foreach($locations->asset as $asset)
            {
                if(\Carbon\Carbon::parse($asset->audit_date)->isPast())
                {
                    $past++;
                } else
                {
                    $age = \Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);
                    switch(true)
                    {
                        case($age < 31):
                            $month++;
                            break;
                        case($age < 90):
                            $quarter++;
                            break;
                        case($age < 180):
                            $half++;
                            break;
                    }
                }
            }
            $data[] = ['name' => $locations->name, 'icon' => $locations->icon, 'past' => $past, 'month' => $month, 'quarter' => $quarter, 'half' => $half];
        }

        return json_encode($data);
    }

}
