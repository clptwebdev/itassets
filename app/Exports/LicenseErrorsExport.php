<?php

namespace App\Exports;

use App\Models\License;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LicenseErrorsExport implements FromArray, WithHeadings, ShouldAutoSize {

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
            , "expiry",
            "contact",
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
                $array['expiry'] = $exportError->expiry;
                $array['contact'] = $exportError->contact;

                $object[] = $array;

            }

            return $object;

        }
    }

}
