<?php

namespace App\Exports;

use App\Models\AUC;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AUCErrorsExport implements FromArray, WithHeadings, ShouldAutoSize {

    private $export;

    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "Name",
            "location_id",
            "purchased_cost",
            "purchased_date",
            "depreciation",
            "type",
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
                $array['location_id'] = $exportError->location_id;
                $array['purchased_cost'] = $exportError->purchased_cost;
                $array['purchased_date'] = $exportError->purchased_date;
                $array['depreciation'] = $exportError->depreciation;
                $array['type'] = $exportError->type;

                $object[] = $array;

            }

            return $object;

        }
    }

}
