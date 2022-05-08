<?php

namespace App\Exports;

use App\Models\Software;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SoftwareErrorsExport implements FromArray, WithHeadings, ShouldAutoSize {

    private $export;

    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "Name",
            "supplier_id",
            "location_id",
            "purchased_cost"
            , "purchased_date"
            , "depreciation",
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
                $array['supplier_id'] = $exportError->supplier_id;
                $array['location_id'] = $exportError->location_id;
                $array['purchased_cost'] = $exportError->purchased_cost;
                $array['purchased_date'] = $exportError->purchased_date;
                $array['depreciation'] = $exportError->depreciation;

                $object[] = $array;

            }

            return $object;

        }
    }

}
