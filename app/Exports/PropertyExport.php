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
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class PropertyExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    use Exportable;

    private $properties;

    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Type",
            "Location",
            "Purchased Date",
            "Purchased Cost",
            "Depreciation",
        ];
    }

    public function array(): array
    {
        $object = [];
        foreach($this->properties as $property)
        {
            $array = [];
            $array['Name'] = $property->name;
            $array['Type'] = $property->getType();
            $array['Location'] = $property->location->name ?? 'Unknown';
            $array['Purchased Date'] = Carbon::parse($property->purchased_date)->format('d\/m\/Y') ?? 'Unknown';
            $array['Purchased Cost'] = '£' . number_format((float)$property->purchased_cost, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation'] = $property->depreciation ?? 'Unknown';
            $object[] = $array;

        }
//        $purchased_details = [];
//        $purchased_details['Purchased Cost'] = 'Total Cost:' . 123;
//        $purchased_details['Purchased Date'] = 'Total Cost After Depreciation:' . 120;
//        array_push($object, $purchased_details);

        return $object;
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

    public function title(): string
    {
        return 'Property';
    }

}
