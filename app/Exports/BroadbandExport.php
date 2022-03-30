<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class BroadbandExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    private $broadbands;

    public function __construct($broadbands)
    {
        $this->broadbands = $broadbands;
    }

    public function headings(): array
    {
        return [
            "name",
            "supplier_id",
            "location_id",
            "purchased_cost",
            "purchased_date",
            "renewal_date",
            "package",
        ];
    }

    public function array(): array
    {

        $object = [];
        foreach($this->broadbands as $broadband)
        {
            $array = [];
            $array["name"] = $broadband->name;
            $array["supplier_id"] = $broadband->supplier->name;
            $array["location_id"] = $broadband->location->name ?? null;
            $array["purchased_cost"] = $broadband->purchased_cost;
            $array["purchased_date"] = \Illuminate\Support\Carbon::parse($broadband->purchased_date)->format('d-M-Y');
            $array["renewal_date"] = \Illuminate\Support\Carbon::parse($broadband->renewal_date)->format('d-M-Y');
            $array["package"] = $broadband->package;
            $object[] = $array;


        }

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:G1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
