<?php

namespace App\Exports;

use App\Models\Accessory;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class accessoryErrorsExport implements FromArray, WithHeadings, ShouldAutoSize {

    private $export;

    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "Name",
            "status_id",
            "supplier_id",
            "manufacturer_id",
            "location_id",
            "order_no",
            "serial_no",
            "purchased_cost"
            , "purchased_date"
            , "warranty"
            , "notes",
        ];
    }

    public function array(): array
    {
        if(! empty($this->export))
        {

            $object = [];
            foreach($this->export as $id => $exportError)
            {
                $array = [];
                $array['Name'] = $exportError->name;
                $array['status_id'] = $exportError->status_id;
                $array['supplier_id'] = $exportError->supplier_id;
                $array['manufacturer_id'] = $exportError->manufacturer_id;
                $array['location_id'] = $exportError->location_id;
                $array['order_no'] = $exportError->order_no;
                $array['serial_no'] = $exportError->serial_no;
                $array['purchased_cost'] = $exportError->purchased_cost;
                $array['purchased_date'] = $exportError->purchased_date;
                $array['warranty'] = $exportError->warranty;
                $array['notes'] = $exportError->notes;

                $object[] = $array;

            }

            return $object;

        }
    }

}
