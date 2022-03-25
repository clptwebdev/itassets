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

use Carbon\Carbon;

class AUCExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents 
{
    use Exportable;

    private $aucs;

    public function __construct($aucs)
    {
        $this->aucs = $aucs;
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
        foreach($this->aucs as $auc)
        {
            $array = [];
            $array['name'] = $auc->name;
            $array['type'] = $auc->getType();
            $array['location_id'] = $auc->location->name ?? 'Unknown';
            $array['purchased_date'] = Carbon::parse($auc->purchased_date)->format('d\/m\/Y') ?? 'Unknown';
            $array['purchased_cost'] = '£'.number_format( (float) $auc->purchased_cost , 2, '.', ',' ) ?? 'Unknown';
            $array['depreciation'] = $auc->depreciation ?? 'Unknown';
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
}
