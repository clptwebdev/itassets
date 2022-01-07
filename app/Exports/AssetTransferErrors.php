<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetTransferErrors implements FromArray , WithHeadings , ShouldAutoSize
{
private $export;
    public function __construct($export)
    {
        $this->export = $export;
    }

    public function headings(): array
    {
        return [
            "id",
            "asset_tag",
            "new_tag",
            "serial_no",
            "location_from",
            "location_to",
            "date",
            "notes",
        ];
    }
    public function array(): array
    {
        if(! empty($this->export))
        {


            $object =  [];
            foreach($this->export as $id => $exportError){
                $array =  [];
                $array['id'] = $exportError->id;
                $array['asset_tag'] = $exportError->asset_tag;
                $array['new_tag'] = $exportError->new_tag;
                $array['serial_no'] = $exportError->serial_no;
                $array['location_from'] = $exportError->location_from;
                $array['location_to'] = $exportError->location_to;
                $array['date'] = $exportError->date;
                $array['notes'] = $exportError->notes;

                $object[] = $array;

            }
           return $object;

        }
    }

}
