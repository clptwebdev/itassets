<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class VehicleExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    private $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Registration",
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
        foreach($this->vehicles as $vehicle)
        {
            $array = [];
            $array["Name"] = $vehicle->name;
            $array["Registration"] = $vehicle->registration;
            $array["Supplier"] = $vehicle->supplier->name ?? '';
            $array["Location"] = $vehicle->location->name ?? '';
            $array["Purchased Cost"] = $vehicle->purchased_cost;
            $array["Purchased Date"] = $vehicle->purchased_date;
            $array["Depreciation"] = $vehicle->depreciation;
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
        return 'Vehicle';
    }

}
