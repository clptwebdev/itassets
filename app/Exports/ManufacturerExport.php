<?php

namespace App\Exports;

use App\Models\Manufacturer;
use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class ManufacturerExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    public function headings(): array
    {
        return [
            "name",
            "supportUrl",
            "supportPhone",
            "supportEmail",
        ];
    }

    public function array(): array
    {
        $manufacturers = Manufacturer::all();
        $object = [];
        foreach($manufacturers as $manufacturer)
        {
            $array = [];
            $array["name"] = $manufacturer->name;
            $array["supportUrl"] = $manufacturer->supportUrl;
            $array["supportPhone"] = $manufacturer->supportPhone;
            $array["supportEmail"] = $manufacturer->supportEmail;
            $object[] = $array;


        }

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
