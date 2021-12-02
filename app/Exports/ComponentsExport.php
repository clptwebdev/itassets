<?php

namespace App\Exports;

use App\Models\Component;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ComponentsExport implements FromArray, WithHeadings
{
    private $components;
    public function __construct($components)
    {
        $this->components = $components;
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
        foreach($this->components as $component)
        {
            $array = [];
            $array["name"] = $component->name;
            $array["status_id"] = $component->status->name ?? 'N/A';
            $array["supplier_id"] = $component->supplier->name?? 'N/A';
            $array["manufacturer_id"] = $component->manufacturer->name ?? 'N/A';
            $array["location_id"] = $component->location->name ?? 'Unassigned';
            $array["order_no"] = $component->order_no;
            $array["serial_no"] = $component->serial_no;
            $array["purchased_cost"] = $component->purchased_cost;
            $array["purchased_date"] = $component->purchased_date;
            $array["warranty"] = $component->warranty;
            $array["notes"] = $component->notes;
            $object[] = $array;


        }

        return $object;
    }
}
