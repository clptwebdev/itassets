<?php

namespace App\Exports;

use App\Models\Location;
use App\Models\Software;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SoftwareExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    private $softwares;

    public function __construct($softwares)
    {
        $this->softwares = $softwares;
    }

    public function headings(): array
    {
        return [
            "name",
            "supplier_id",
            "location_id",
            "purchased_cost",
            "purchased_date",
            "depreciation",
        ];
    }

    public function array(): array
    {

        $object = [];
        foreach($this->softwares as $software)
        {
            $array = [];
            $array["name"] = $software->name;
            $array["supplier_id"] = $software->supplier_id;
            $array["location_id"] = $software->location_id ?? null;
            $array["purchased_cost"] = $software->purchased_cost;
            $array["purchased_date"] = $software->purchased_date;
            $array["depreciation"] = $software->depreciation;
            $object[] = $array;


        }

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:F1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
