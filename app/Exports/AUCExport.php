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

class AUCExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    use Exportable;

    private $aucs;

    public function __construct($aucs)
    {
        $this->aucs = $aucs;
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
        foreach($this->aucs as $auc)
        {

            $array = [];
            $array['Name'] = $auc->name;
            $array['Type'] = $auc->getType();
            $array['Location'] = $auc->location->name ?? 'Unknown';
            $array['Purchased Date'] = Carbon::parse($auc->purchased_date)->format('d\/m\/Y') ?? 'Unknown';
            $array['Purchased Cost'] = '£' . number_format((float)$auc->purchased_cost, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation'] = $auc->depreciation ?? 'Unknown';
            $object[] = $array;

        }

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
        return 'AUC';
    }

}
