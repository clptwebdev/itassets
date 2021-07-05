<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    //
    public function getPieChart(){
        $locations = auth()->user()->locations;
        $assets = auth()->user()->location_assets()->count();
        $data = array();

        foreach ($locations as $location) {
            $row['name'] = $location->name;
            $row['icon'] = $location->icon;
            $row['asset'] = (count($location->asset) / $assets)* 100;
            $data[] = $row;
        }

        return json_encode($data);
    }
}
