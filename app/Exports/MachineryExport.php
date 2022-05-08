<?php

namespace App\Exports;

use App\Models\Machinery;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;

class MachineryExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle {

    private $machineries;

    public function __construct($machineries)
    {
        $this->machineries = $machineries;
    }

    public function headings(): array
    {
        return [
            "Name",
            "Description",
            "Supplier",
            "Location",
            "Purchased Cost",
            "Purchased Date",
            "Depreciation",
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
        foreach($this->machineries as $machinery)
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
            $bf = $machinery->depreciation_value_by_date($startDate);
            $cf = $machinery->depreciation_value_by_date($nextStartDate);
            $array = [];
            $array["Name"] = $machinery->name;
            $array["Description"] = $machinery->description;
            $array["Supplier"] = $machinery->supplier_id;
            $array["Location"] = $machinery->location_id ?? null;
            $array["Purchased Cost"] = $machinery->purchased_cost;
            $array["Purchased Date"] = Carbon::parse($machinery->purchased_date)->format('d\/m\/Y') ?? 'Unknown';
            $array["Depreciation"] = $machinery->depreciation;
            $array['Cost B/Fwd'] = '£' . number_format((float)$bf, 2, '.', ',') ?? 'Unknown';
            $array['Cost C/Fwd'] = '£' . number_format((float)$cf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation B/Fwd'] = '£' . number_format((float)$machinery->purchased_cost - $bf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation Charge'] = '£' . number_format((float)$bf - $cf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation C/Fwd'] = '£' . number_format((float)$machinery->purchased_cost - $cf, 2, '.', ',') ?? 'Unknown';
            if(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear() >= $machinery->purchased_date)
            {
                $array['NBV'] = '£' . number_format((float)$machinery->depreciation_value_by_date(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear()), 2, '.', ',');
            } else
            {
                $array['NBV'] = '£' . 0.00;
            }
            $Cost_B_Fwd += $bf;
            $Cost_C_Fwd += $cf;
            $Depreciation_B_Fwd += $machinery->purchased_cost - $bf;
            $Depreciation_charge += $bf - $cf;
            $Depreciation_C_Fwd += $machinery->purchased_cost - $cf;
            $nbv += $machinery->depreciation_value_by_date(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear());
            $object[] = $array;


        }
        $purchased_details = [];
        $purchased_details['Name'] = 'Total Machinery: ' . $this->machineries->count();
        $purchased_details['Description'] = '';
        $purchased_details['Supplier'] = '';
        $purchased_details['Location'] = '';
        $purchased_details['Purchased Cost'] = 'Total Cost: £' . $this->machineries->sum('purchased_cost');
        $purchased_details['Purchased Date'] = '';
        $purchased_details['Depreciation'] = '';
        $purchased_details['Cost B/Fwd'] = 'Total: £' . number_format((float)$Cost_B_Fwd, 2, '.', ',');
        $purchased_details['Cost C/Fwd'] = 'Total: £' . number_format((float)$Cost_C_Fwd, 2, '.', ',');
        $purchased_details['Depreciation B/Fwd'] = 'Total: £' . number_format((float)$Depreciation_B_Fwd, 2, '.', ',');
        $purchased_details['Depreciation Charge'] = 'Total: £' . number_format((float)$Depreciation_charge, 2, '.', ',');
        $purchased_details['Depreciation C/Fwd'] = 'Total: £' . number_format((float)$Depreciation_C_Fwd, 2, '.', ',');
        $purchased_details['nbv'] = 'Total: £' . number_format((float)$nbv, 2, '.', ',');
        array_push($object, $purchased_details);

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $this->machineries->count() + 2;
                $cellRange = 'A1:M1'; // All headers
                $cellRange2 = 'A' . $lastRow . ':M' . $lastRow; // Last Row
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getBorders()->getAllBorders()->setBorderStyle(true);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(11)->setBold(1);
            },
        ];
    }

    public function title(): string
    {
        return 'Machinery';
    }

}
