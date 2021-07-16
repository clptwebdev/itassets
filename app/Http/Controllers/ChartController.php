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

    public function getAssetValueChart(){
        $data = array();
        foreach(auth()->user()->locations as $locations){
            $yearValues = array();
            for($i=0; $i<4; $i++){
                $y = \Carbon\Carbon::now()->addYears($i);
                $yv = 0;
                foreach($locations->asset as $asset){
                    $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($asset->model->depreciation->years);
                    if($eol->isPast()){}else{
                        $age = $y->floatDiffInYears($asset->purchased_date);
                        $percent = 100 / $asset->model->depreciation->years;
                        $percentage = floor($age)*$percent;
                        $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                        if($dep < 0){ $dep = 0;}
                        $yv += round($dep);
                    }
                }
                $yearValues[$y->year] = $yv;
                unset($age);
                unset($percentage);
                unset($dep);
            }
            $data[] = ['name' => $locations->name, 'icon' => $locations->icon, 'years'=> $yearValues ];
        }
        return json_encode($data);
    }

    public function getAssetAuditChart(){
        $data = array();
        foreach(auth()->user()->locations as $locations){
            $past = 0; $month = 0; $quarter = 0; $half = 0;
            foreach($locations->asset as $asset){
                if(\Carbon\Carbon::parse($asset->audit_date)->isPast()){
                    $past++;
                }else{
                    $age = \Carbon\Carbon::now()->floatDiffInDays($asset->audit_date);
                    switch(true){
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
            $data[] = ['name'=>$locations->name, 'icon'=>$locations->icon, 'past'=>$past, 'month'=>$month, 'quarter'=> $quarter, 'half'=>$half];
        }
        return json_encode($data);
    }
}
