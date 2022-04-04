<?php

namespace App\Exports;

use App\Models\FFE;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FFEErrorsExport implements FromArray, WithHeadings, ShouldAutoSize {

    private $export;

    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "Name",
            "serial_no",
            "status_id",
            "purchased_date",
            "purchased_cost",
            "donated",
            "supplier_id",
            "manufacturer_id",
            "order_no",
            "warranty",
            "depreciation_id",
            "location_id",
            "room",
            "notes",
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
                $array['serial_no'] = $exportError->serial_no;
                $array['status_id'] = $exportError->status_id;
                $array['purchased_date'] = $exportError->purchased_date;                
                $array['purchased_cost'] = $exportError->purchased_cost;
                $array['donated'] = $exportError->donated;
                $array['supplier_id'] = $exportError->supplier_id;
                $array['manufacturer_id'] = $exportError->manufacturer_id;
                $array['order_no'] = $exportError->order_no;
                $array['warranty'] = $exportError->warranty;
                $array['depreciation_id'] = $exportError->depreciation_id;
                $array['location_id'] = $exportError->location_id;
                $array['room'] = $exportError->room;
                $array['notes'] = $exportError->notes;

                $object[] = $array;

            }

            return $object;

        }
    }

}
