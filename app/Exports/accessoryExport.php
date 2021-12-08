<?php

namespace App\Exports;

use App\Models\Accessory;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class accessoryExport implements FromArray, WithHeadings
{
    private $accessories;
    public function __construct($accessories)
    {
        $this->accessories = $accessories;
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
        foreach($this->accessories as $accessory)
        {
            $array = [];
            $array["name"] = $accessory->name;
            $array["status_id"] = $accessory->status->name ?? 'N/A';
            $array["supplier_id"] = $accessory->supplier->name?? 'N/A';
            $array["manufacturer_id"] = $accessory->manufacturer->name ?? 'N/A';
            $array["location_id"] = $accessory->location->name;
            $array["order_no"] = $accessory->order_no;
            $array["serial_no"] = $accessory->serial_no;
            $array["purchased_cost"] = $accessory->purchased_cost;
            $array["purchased_date"] = $accessory->purchased_date;
            $array["warranty"] = $accessory->warranty;
            $array["notes"] = $accessory->notes;
            $object[] = $array;


        }

        return $object;
    }
}
