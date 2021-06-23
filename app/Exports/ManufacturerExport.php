<?php

namespace App\Exports;

use App\Models\Manufacturer;
use App\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ManufacturerExport implements FromArray, WithHeadings {

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

}
