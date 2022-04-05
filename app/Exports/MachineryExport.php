<?php

namespace App\Exports;

use App\Models\Machinery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class MachineryExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    private $machineries;

    public function __construct($machineries)
    {
        $this->machineries = $machineries;
    }

    public function headings(): array
    {
        return [
            "name",
            "description",
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
        foreach($this->machineries as $machinery)
        {
            $array = [];
            $array["name"] = $machinery->name;
            $array["description"] = $machinery->description;
            $array["supplier_id"] = $machinery->supplier_id;
            $array["location_id"] = $machinery->location_id ?? null;
            $array["purchased_cost"] = $machinery->purchased_cost;
            $array["purchased_date"] = $machinery->purchased_date;
            $array["depreciation"] = $machinery->depreciation;
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
