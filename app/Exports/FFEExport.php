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
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

use Carbon\Carbon;

class FFEExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

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
            "Serial No",
            "Status",
            "Purchased Date",
            "Purchased Cost",
            "Donated",
            "Supplier",
            "Manufacturer",
            "Order No",
            "Warranty",
            "Depreciation",
            "Location",
            "Room",
            "Notes",
        ];
    }

    public function array(): array
    {
        $object = [];
        foreach($this->ffes as $ffe)
        {
            $array = [];
            $array['Name'] = $ffe->name;
            $array['Serial No'] = $ffe->serial_no;
            $array['Status'] = $ffe->status->name ?? '';
            $array['Purchased Date'] = Carbon::parse($ffe->purchased_date)->format('d\/m\/Y') ?? 'Unknown';;
            $array['Purchased Cost'] = $ffe->purchased_cost;
            $array['Donated'] = $ffe->donated;
            $array['Supplier'] = $ffe->supplier->name ?? '';
            $array['Manufacturer'] = $ffe->manufacturer->name ?? '';
            $array['Order No'] = $ffe->order_no;
            $array['Warranty'] = $ffe->warranty;
            $array['Depreciation'] = $ffe->depreciation_id;
            $array['Location'] = $ffe->location->name;
            $array['Room'] = $ffe->room;
            $array['Notes'] = $ffe->notes;
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

    public function title(): string
    {
        return 'FFE';
    }

}
