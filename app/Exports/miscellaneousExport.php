<?php

namespace App\Exports;

use App\Models\Miscellanea;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class miscellaneousExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    private $miscellaneous;

    public function __construct($miscellaneous)
    {
        $this->miscellaneous = $miscellaneous;
    }

    public function headings(): array
    {
        return [
            "name",
            "status_id",
            "supplier_id",
            "manufacturer_id",
            "location_id",
            "order_no",
            "serial_no",
            "purchased_cost",
            "purchased_date",
            "warranty",
            "notes",
        ];
    }

    public function array(): array
    {

        $object = [];
        foreach($this->miscellaneous as $miscellanea)
        {
            $array = [];
            $array["name"] = $miscellanea->name;
            $array["status_id"] = $miscellanea->status->name ?? 'N/A';
            $array["supplier_id"] = $miscellanea->supplier->name ?? 'N/A';
            $array["manufacturer_id"] = $miscellanea->manufacturer->name ?? 'N/A';
            $array["location_id"] = $miscellanea->location->name;
            $array["order_no"] = $miscellanea->order_no;
            $array["serial_no"] = $miscellanea->serial_no;
            $array["purchased_cost"] = $miscellanea->purchased_cost;
            $array["purchased_date"] = $miscellanea->purchased_date;
            $array["warranty"] = $miscellanea->warranty;
            $array["notes"] = $miscellanea->notes;
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
