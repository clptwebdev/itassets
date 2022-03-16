<?php

namespace App\Exports;

use App\Models\Property;
use App\Models\Location;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class PropertyExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents 
{
    use Exportable;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function headings(): array
    {
        return [
            "name",
            "type",
            "location",
            "purchased_date",
            "purchased_cost",
            "depreciation"
        ];
    }

    public function array(): array
    {
        $object = [];
        foreach($this->properties as $property)
        {
            $array = [];
            $array['name'] = $property->name;
            $array['type'] = $property->getType();
            $array['location_id'] = $property->location->name ?? 'Unknown';
            $array['purchased_date'] = $property->purchased_date ?? 'Unknown';
            $array['purchased_cost'] = $property->purchased_cost ?? 'Unknown';
            $array['depreciation'] = $property->depreciation ?? 'Unknown';
            $object[] = $array;

        }

        /* $array = [];
        $array['name'] = ' ';
        $array['type'] = ' ';
        $array['location_id'] = ' ';
        $array['purchased_date'] = ' ';
        $array['purchased_cost'] = '£1,450,000.45';
        $array['depreciation'] = ' ';
        $object[] = $array;

        return $object; */

    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:F1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }
}
