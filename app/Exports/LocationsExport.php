<?php

namespace App\Exports;

use App\Models\Location;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LocationsExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    public function headings(): array
    {
        return [
            "name",
            "address_1",
            "address_2",
            "city",
            "county",
            "post_code",
            "telephone",
            "email",
        ];
    }

    public function array(): array
    {
        $locations = Location::all();
        $object = [];
        foreach($locations as $location)
        {
            $array = [];
            $array["name"] = $location->name;
            $array["address_1"] = $location->address_1;
            $array["address_2"] = $location->address_2 ?? null;
            $array["city"] = $location->city;
            $array["county"] = $location->county;
            $array["post_code"] = $location->post_code;
            $array["telephone"] = $location->telephone;
            $array["email"] = $location->email;
            $object[] = $array;


        }

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:I1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
