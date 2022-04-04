<?php

namespace App\Exports;

use App\Models\FFE;
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

use Carbon\Carbon;

class FFEExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents 
{
    use Exportable;

    private $ffes;

    public function __construct($ffes)
    {
        $this->ffes = $ffes;
    }

    public function headings(): array
    {
        return [
            "Name",
            "serial_no",
            "status_id",
            "purchased_date",
            "purchased_cost",
            "donated",
            "supplier_id",
            "manufacturer_id",
            "order_no",
            "warranty",
            "depreciation_id",
            "location_id",
            "room",
            "notes",
        ];
    }

    public function array(): array
    {
        $object = [];
        foreach($this->ffes as $ffe)
        {
            $array = [];
            $array['Name'] = $ffe->name;
            $array['serial_no'] = $ffe->serial_no;
            $array['status_id'] = $ffe->status->name ?? '';
            $array['purchased_date'] = Carbon::parse($ffe->purchased_date)->format('d\/m\/Y') ?? 'Unknown';;                
            $array['purchased_cost'] = $ffe->purchased_cost;
            $array['donated'] = $ffe->donated;
            $array['supplier_id'] = $ffe->supplier->name ?? '';
            $array['manufacturer_id'] = $ffe->manufacturer->name ?? '';
            $array['order_no'] = $ffe->order_no;
            $array['warranty'] = $ffe->warranty;
            $array['depreciation_id'] = $ffe->depreciation_id;
            $array['location_id'] = $ffe->location->name;
            $array['room'] = $ffe->room;
            $array['notes'] = $ffe->notes;
            $object[] = $array;

        }

        return $object;

    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:N1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }
}
