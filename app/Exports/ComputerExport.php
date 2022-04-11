<?php

namespace App\Exports;

use Carbon\Carbon;
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
            "Cost B/Fwd" . ' (01/09/' . \Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->format('Y') . ')',
            "Cost C/Fwd" . ' (31/08/' . \Carbon\Carbon::parse('08/31/' . \Carbon\Carbon::now()->addYear()->format('Y'))->format('Y') . ')',
            "Depreciation B/Fwd" . ' (01/09/' . \Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->format('Y') . ')',
            "Depreciation Charge",
            "Depreciation C/Fwd" . ' (31/08/' . \Carbon\Carbon::parse('08/31/' . \Carbon\Carbon::now()->addYear()->format('Y'))->format('Y') . ')',
            "NBV " . \Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear()->format('Y'),
        ];
    }

    public function array(): array
    {
        $object = [];
        $Cost_B_Fwd = 0;
        $Cost_C_Fwd = 0;
        $Depreciation_B_Fwd = 0;
        $Depreciation_charge = 0;
        $Depreciation_C_Fwd = 0;
        $nbv = 0;
        foreach($this->assets as $asset)
        {
            //Maths Calculations
            $now = \Carbon\Carbon::now();
            $startDate = \Carbon\Carbon::parse('09/01/' . $now->format('Y'));
            $nextYear = \Carbon\Carbon::now()->addYear()->format('Y');
            $nextStartDate = \Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->addYear()->format('Y'));
            $endDate = \Carbon\Carbon::parse('08/31/' . $nextYear);
            if(! $startDate->isPast())
            {
                $startDate->subYear();
                $endDate->subYear();
                $nextStartDate->subYear();
            }
            $bf = $asset->depreciation_value_by_date($startDate);
            $cf = $asset->depreciation_value_by_date($nextStartDate);

            $array = [];
            $array['Details'] = $asset->name;
            $array['Purchased Cost'] = $asset->purchased_cost;
            $array['Location'] = $asset->location->name ?? 'Unknown';
            $array['Created Date'] = \Illuminate\Support\Carbon::parse($asset->created_at)->format('d-M-Y') ?? 'Unknown';
            $array['Cost B/Fwd'] = '£' . number_format((float)$bf, 2, '.', ',') ?? 'Unknown';
            $array['Cost C/Fwd'] = '£' . number_format((float)$cf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation B/Fwd'] = '£' . number_format((float)$asset->purchased_cost - $bf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation Charge'] = '£' . number_format((float)$bf - $cf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation C/Fwd'] = '£' . number_format((float)$asset->purchased_cost - $cf, 2, '.', ',') ?? 'Unknown';
            if(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear() >= $asset->purchased_date)
            {
                $array['NBV'] = '£' . number_format((float)$asset->depreciation_value_by_date(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear()), 2, '.', ',');
            } else
            {
                $array['NBV'] = '£' . 0.00;
            }
            $Cost_B_Fwd += $bf;
            $Cost_C_Fwd += $cf;
            $Depreciation_B_Fwd += $asset->purchased_cost - $bf;
            $Depreciation_charge += $bf - $cf;
            $Depreciation_C_Fwd += $asset->purchased_cost - $cf;
            $nbv += $asset->depreciation_value_by_date(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear());
            $object[] = $array;

        }
        $purchased_details = [];
        $purchased_details['Details'] = 'Total Assets: ' . $this->assets->count();
        $purchased_details['Purchased Cost'] = 'Total Cost: £' . $this->assets->sum('purchased_cost');
        $purchased_details['Location'] = '';
        $purchased_details['Created Date'] = '';
        $purchased_details['Cost B/Fwd'] = 'Total: £' . $Cost_B_Fwd;
        $purchased_details['Cost C/Fwd'] = 'Total: £' . $Cost_C_Fwd;
        $purchased_details['Depreciation B/Fwd'] = 'Total: £' . $Depreciation_B_Fwd;
        $purchased_details['Depreciation Charge'] = 'Total: £' . $Depreciation_charge;
        $purchased_details['Depreciation C/Fwd'] = 'Total: £' . $Depreciation_C_Fwd;
        $purchased_details['nbv'] = 'Total: £' . $nbv;

        array_push($object, $purchased_details);

        return $object;

    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $this->assets->count() + 2;
                $cellRange = 'A1:J1'; // All headers
                $cellRange2 = 'A' . $lastRow . ':J' . $lastRow; // Last Row
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getBorders()->getAllBorders()->setBorderStyle(true);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(11)->setBold(1);
            },
        ];
    }

    public function title(): string
    {
        return 'Computer Equipment';
    }

}
