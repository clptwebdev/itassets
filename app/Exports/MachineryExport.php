<?php

namespace App\Exports;

use App\Models\Machinery;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class MachineryExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    private $machineries;

    public function __construct($machineries)
    {
        $this->machineries = $machineries;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Description",
            "Supplier",
            "Location",
            "Purchased Cost",
            "Purchased Date",
            "Depreciation",
        ];
    }

    public function array(): array
    {

        $object = [];
        foreach($this->machineries as $machinery)
        {
            $array = [];
            $array["Name"] = $machinery->name;
            $array["Description"] = $machinery->description;
            $array["Supplier"] = $machinery->supplier_id;
            $array["Location"] = $machinery->location_id ?? null;
            $array["Purchased Cost"] = $machinery->purchased_cost;
            $array["Purchased Date"] = $machinery->purchased_date;
            $array["Depreciation"] = $machinery->depreciation;
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

    public function title(): string
    {
        return 'Machinery';
    }

}
