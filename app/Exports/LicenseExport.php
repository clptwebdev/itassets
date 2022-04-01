<?php

namespace App\Exports;

use App\Models\License;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LicenseExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    private $licenses;

    public function __construct($licenses)
    {
        $this->licenses = $licenses;
    }

    public function headings(): array
    {
        return [
            "name",
            "supplier_id",
            "location_id",
            "purchased_cost",
            "expiry",
            "contact",
        ];
    }

    public function array(): array
    {

        $object = [];
        foreach($this->licenses as $license)
        {
            $array = [];
            $array["name"] = $license->name;
            $array["supplier_id"] = $license->supplier->name ?? null;
            $array["location_id"] = $license->location->name ?? null;
            $array["purchased_cost"] = $license->purchased_cost;
            $array["expiry"] = \Illuminate\Support\Carbon::parse($license->expiry)->format('d-M-Y') ?? null;
            $array["contact"] = $license->contact ?? null;
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
