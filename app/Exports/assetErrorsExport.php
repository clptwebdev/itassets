<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class assetErrorsExport implements FromArray , WithHeadings , ShouldAutoSize
{
private $export;
    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "asset_tag",
            "name",
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
            , "audit_date"
        ];
    }
    public function array(): array
    {
        if(! empty($this->export))
        {


            $object =  [];
            foreach($this->export as $id => $exportError){
                $array =  [];
                $array['asset_tag'] = $exportError->asset_tag;
                $array['name'] = $exportError->name;
                $array['serial_no'] = $exportError->serial_no;
                $array['asset_model'] = $exportError->asset_model_id;
                $array['status_id'] = $exportError->status_id;
                $array['purchased_date'] = $exportError->purchased_date;
                $array['purchased_cost'] = $exportError->purchased_cost;
                $array['supplier_id'] = $exportError->supplier_id;
                $array['manufacturer_id'] = $exportError->manufacturer_id;
                $array['order_no'] =$exportError->order_no;
                $array['warranty'] = $exportError->warranty;
                $array['location_id'] = $exportError->location_id;
                $array['audit_date'] = $exportError->audit_date;

                $object[] = $array;

            }
           return $object;

        }
    }

}
