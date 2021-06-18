<?php

namespace App\Exports;

use App\Models\Asset;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AssetExport implements FromArray, WithHeadings {

    use Exportable;

    public function headings(): array
    {
        return [
            "asset_tag",
            "serial_no",
            "asset_model",
            "status_id",
            "purchased_date",
            "purchased_cost",
            "supplier_id",
            "manufacturer_id"
            , "order_no"
            , "warranty"
            , "location_id"
            , "user_id"
            , "audit_date"
        ];
    }

    public function array(): array
    {
        $assets = Asset::all();
        $object =  [];
        foreach($assets as $asset){
            $array =  [];
            $array['asset_tag'] = $asset->asset_tag;
            $array['serial_no'] = $asset->serial_no;
            $array['asset_model'] = $asset->model->name;
            $array['status_id'] ="Booked out"; //$asset->status->name;
            $array['purchased_date'] = $asset->purchased_date;
            $array['purchased_cost'] = $asset->purchased_cost;
            $array['supplier_id'] = $asset->supplier->name;
            $array['manufacturer_id'] = $asset->manufacturer->name;
            $array['order_no'] = $asset->order_no;
            $array['warranty'] = $asset->warranty;
            $array['location_id'] = $asset->location->name;
            $array['user_id'] = $asset->user->name;
            $array['audit_date'] = $asset->audit_date;
            $object[] = $array;

        }
        return $object;

    }
//
//        return DB::table("assets")->select(
//            "asset_tag",
//            "serial_no",
//            "asset_model",
//            "status_id",
//            "purchased_date",
//            "purchased_cost",
//            \App\M,
//            "manufacturer_id",
//             "order_no",
//             "warranty",
//             "location_id",
//             "user_id",
//             "audit_date")->get();


}
