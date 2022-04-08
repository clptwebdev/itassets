<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class ComputerExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    use Exportable;

    private $assets;

    public function __construct($assets)
    {
        $this->assets = $assets;
    }

    public function headings(): array
    {
        return [
            'Details',
            'Purchased Cost',
            'Location',
            'Created Date',
        ];
    }

    public function array(): array
    {
        $object = [];
        foreach($this->assets as $asset)
        {
            $array = [];
            $array['Details'] = $asset->name;
            $array['Purchased Cost'] = $asset->purchased_cost;
            $array['Location'] = $asset->location->name ?? 'Unknown';
            $array['Created Date'] = \Illuminate\Support\Carbon::parse($asset->created_at)->format('d-M-Y') ?? 'Unknown';
            $object[] = $array;

        }

        return $object;

    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:D1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

    public function title(): string
    {
        return 'Computer Equipment';
    }

}
