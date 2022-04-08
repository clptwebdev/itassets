<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class VehicleExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    private $vehicles;

    public function __construct($vehicles)
    {
        $this->vehicles = $vehicles;
    }

    public function headings(): array
    {
        return [
            "name",
            "registration",
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
        foreach($this->vehicles as $vehicle)
        {
            $array = [];
            $array["name"] = $vehicle->name;
            $array["registration"] = $vehicle->registration;
            $array["supplier_id"] = $vehicle->supplier->name ?? '';
            $array["location_id"] = $vehicle->location->name ?? '';
            $array["purchased_cost"] = $vehicle->purchased_cost;
            $array["purchased_date"] = $vehicle->purchased_date;
            $array["depreciation"] = $vehicle->depreciation;
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
