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
        foreach($this->ffes as $ffe)
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
            $bf = $ffe->depreciation_value_by_date($startDate);
            $cf = $ffe->depreciation_value_by_date($nextStartDate);

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
            $array['Cost B/Fwd'] = '£' . number_format((float)$bf, 2, '.', ',') ?? 'Unknown';
            $array['Cost C/Fwd'] = '£' . number_format((float)$cf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation B/Fwd'] = '£' . number_format((float)$ffe->purchased_cost - $bf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation Charge'] = '£' . number_format((float)$bf - $cf, 2, '.', ',') ?? 'Unknown';
            $array['Depreciation C/Fwd'] = '£' . number_format((float)$ffe->purchased_cost - $cf, 2, '.', ',') ?? 'Unknown';
            if(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear() >= $ffe->purchased_date)
            {
                $array['NBV'] = '£' . number_format((float)$ffe->depreciation_value_by_date(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear()), 2, '.', ',');
            } else
            {
                $array['NBV'] = '£' . 0.00;
            }
            $Cost_B_Fwd += $bf;
            $Cost_C_Fwd += $cf;
            $Depreciation_B_Fwd += $ffe->purchased_cost - $bf;
            $Depreciation_charge += $bf - $cf;
            $Depreciation_C_Fwd += $ffe->purchased_cost - $cf;
            $nbv += $ffe->depreciation_value_by_date(\Carbon\Carbon::parse('09/01/' . \Carbon\Carbon::now()->format('Y'))->subYear());
            $object[] = $array;

        }
        $purchased_details = [];
        $purchased_details['Name'] = 'Total FFEs: ' . $this->ffes->count();
        $purchased_details['Serial No'] = '';
        $purchased_details['Status'] = '';
        $purchased_details['Purchased Date'] = '';
        $purchased_details['Purchased Cost'] = 'Total Cost: £' . $this->ffes->sum('purchased_cost');
        $purchased_details['Donated'] = '';
        $purchased_details['Supplier'] = '';
        $purchased_details['Manufacturer'] = '';
        $purchased_details['Order No'] = '';
        $purchased_details['Warranty'] = '';
        $purchased_details['Depreciation'] = '';
        $purchased_details['Location'] = '';
        $purchased_details['Room'] = '';
        $purchased_details['Notes'] = '';
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
                $lastRow = $this->ffes->count() + 2;
                $cellRange = 'A1:T1'; // All headers
                $cellRange2 = 'A' . $lastRow . ':T' . $lastRow; // Last Row
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getBorders()->getAllBorders()->setBorderStyle(true);
                $event->sheet->getDelegate()->getStyle($cellRange2)->getFont()->setSize(11)->setBold(1);
            },
        ];
    }

    public function title(): string
    {
        return 'FFE';
    }

}
