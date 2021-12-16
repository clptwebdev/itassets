<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetDisposalErrors implements FromArray , WithHeadings , ShouldAutoSize
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
            "serial_no",
            "location_id",
            "date",
            "reason",
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
                $array['serial_no'] = $exportError->serial_no;
                $array['location_id'] = $exportError->location_id;
                $array['date'] = $exportError->date;
                $array['reason'] = $exportError->reason;

                $object[] = $array;

            }
           return $object;

        }
    }

}
