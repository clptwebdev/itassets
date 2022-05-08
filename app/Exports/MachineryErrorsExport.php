<?php

namespace App\Exports;

use App\Models\Machinery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MachineryErrorsExport implements FromArray, WithHeadings, ShouldAutoSize {

    private $export;

    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "name",
            "serial_no",
            "manufacturer_id",
            "purchased_date",
            "purchased_cost",
            "donated",
            "order_no",
            "supplier_id",
            "depreciation",
            "warranty",
            "location_id",
            "description",
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
                $array['name'] = $exportError->name;
                $array['serial_no'] = $exportError->serial_no;
                $array['manufacturer_id'] = $exportError->manufacturer_id;#
                $array['purchased_date'] = $exportError->purchased_date;
                $array['purchased_cost'] = $exportError->purchased_cost;
                $array['donated'] = $exportError->donated;
                $array['order_no'] = $exportError->order_no;
                $array['supplier_id'] = $exportError->supplier_id;
                $array['depreciation'] = $exportError->depreciation;
                $array['warranty'] = $exportError->warranty;
                $array['location_id'] = $exportError->location_id;
                $array['description'] = $exportError->description;

                $object[] = $array;

            }

            return $object;

        }
    }

}
