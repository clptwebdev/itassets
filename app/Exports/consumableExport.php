<?php

namespace App\Exports;

use App\Models\Consumable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class consumableExport implements FromArray, WithHeadings
{
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
        $consumables = \App\Models\Consumable::all();
        $object = [];
        foreach($consumables as $consumable)
        {
            $array = [];
            $array["name"] = $consumable->name;
            $array["status_id"] = $consumable->status->name ?? 'N/A';
            $array["supplier_id"] = $consumable->supplier->name?? 'N/A';
            $array["manufacturer_id"] = $consumable->manufacturer->name ?? 'N/A';
            $array["location_id"] = $consumable->location->name;
            $array["order_no"] = $consumable->order_no;
            $array["serial_no"] = $consumable->serial_no;
            $array["purchased_cost"] = $consumable->purchased_cost;
            $array["purchased_date"] = $consumable->purchased_date;
            $array["warranty"] = $consumable->warranty;
            $array["notes"] = $consumable->notes;
            $object[] = $array;


        }

        return $object;
    }
}
