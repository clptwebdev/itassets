<?php

namespace App\Exports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SupplierExport implements FromArray, withHeadings, ShouldAutoSize, WithEvents {

    public function headings(): array
    {
        return [
            "name",
            "address_1",
            "address_2",
            "city",
            "county",
            "postcode",
            "telephone",
            "fax",
            "email",
            "url",
            "notes",
        ];
    }

    public function array(): array
    {
        $suppliers = Supplier::all();
        $object = [];
        foreach($suppliers as $supplier)
        {
            $array = [];
            $array["name"] = $supplier->name;
            $array["address_1"] = $supplier->address_1;
            $array["address_2"] = $supplier->address_2 ?? null;
            $array["city"] = $supplier->city;
            $array["county"] = $supplier->county;
            $array["postcode"] = $supplier->postcode;
            $array["telephone"] = $supplier->telephone;
            $array["fax"] = $supplier->fax;
            $array["email"] = $supplier->email;
            $array["url"] = $supplier->url;
            $array["notes"] = $supplier->notes;
            $object[] = $array;


        }

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:K1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
