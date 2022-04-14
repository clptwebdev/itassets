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
    private $now;
    private $startDate;
    private $nextYear;
    private $nextStartDate;
    private $endDate;
    private $nbvYear1;
    private $nbvYear2;

    public function __construct($assets)
    {
        $this->assets = $assets;
         //Maths Calculations
         $this->now = Carbon::now();
         $this->startDate = Carbon::parse('09/01/' . $this->now->format('Y'));
         $this->nextYear = Carbon::now()->addYear()->format('Y');
         $this->nextStartDate = Carbon::parse('09/01/' . Carbon::now()->addYear()->format('Y'));
         $this->endDate = Carbon::parse('08/31/' . $this->nextYear);
         if(! $this->startDate->isPast())
         {
             $this->startDate->subYear();
             $this->endDate->subYear();
             $this->nextStartDate->subYear();
         }
 
         $this->nbvYear1 = Carbon::parse($this->startDate->format('d-m-Y'))->subYear();
         $this->nbvYear2 = Carbon::parse($this->nbvYear1->format('d-m-Y'))->subYear();

         $this->row = 2;
         $archived = [];
    }

    public function headings(): array
    {
        return [
            "Name",
            "Cost",
            "Date",
            "Cost B/Fwd (" . $this->startDate->format('d\/m\/Y').")",
            "Additions",
            "Disposals",
            "Cost C/Fwd (" . $this->endDate->format('d\/m\/Y').")",
            "Depn B/Fwd (" . $this->startDate->format('d\/m\/Y').")",
            "Depn Charge",
            "Depn Disposal",
            "Depn C/Fwd (" . $this->endDate->format('d\/m\/Y').")",
            "NBV (" . $this->nbvYear1->format('Y').")",
            "NBV (" . $this->nbvYear2->format('Y').")",
        ];
    }

    public function array(): array
    {
        $now = $this->now;
        $startDate = $this->startDate;
        $nextYear = $this->nextYear;
        $nextStartDate = $this->nextStartDate;
        $endDate = $this->endDate;
        $nbvYear1 = $this->nbvYear1;
        $nbvYear2 = $this->nbvYear2;

        $object = [];
        $costBFwd = 0;
        $additions = 0;
        $disposals = 0;
        $costCFwd = 0;
        $depBFwd = 0;
        $depCharge = 0;
        $depDisposal = 0;
        $depCFwd = 0;
        $nbv1 = 0;
        $nbv2 = 0;

        
        foreach($this->assets as $asset)
        {
            $bf = $asset->depreciation_value_by_date($startDate);
            $cf = $asset->depreciation_value_by_date($nextStartDate);

            $depEndDate = 0;
            $monthsStart = 0;
            $monthsCharged = 0;
            $monthsEnd = 0;

            $array = [];
            $asset->asset_tag ? $name = $asset->name.' ('.$asset->asset_tag.')' : $name = $asset->name;
            $array['Name'] = $name;
            $array['Purchased Cost'] = number_format((float)$asset->purchased_cost, 2, '.', ',');

            $purchased_date = Carbon::parse($asset->purchased_date);
            $array['Purchased Date'] = $purchased_date->format('d\/m\/Y') ?? '-';
            
            $array['Cost B/Fwd'] = number_format((float)$bf, 2, '.', ',') ?? '0.00';
            $purchased_date > $startDate? $add = $asset->purchased_cost : $add = 0;
            $array['Additions'] = $add ?? '-';
            $asset->archived_cost ? $ac = number_format((float)$asset->archived_cost, 2, '.', ',') : $ac = '0';
            $array['Disposals'] = $ac ?? '-';
            $array['Cost C/Fwd'] = number_format((float)$cf, 2, '.', ',') ?? '0.00';
            $array['Depn B/Fwd'] = number_format((float)$asset->purchased_cost - $bf, 2, '.', ',') ?? '0.00';
            $array['Depn Charge'] = number_format((float)$bf - $cf, 2, '.', ',') ?? '-';
            $array['Depn Disposal'] =  '-';
            $array['Depn C/Fwd'] = number_format((float)$asset->purchased_cost - $cf, 2, '.', ',') ?? '0.00';

            if($nbvYear1 >= $asset->purchased_date){
                $array['NBV '.$nbvYear1] = number_format((float)$asset->depreciation_value_by_date($nbvYear1), 2, '.', ',');
            }else{
                $array['NBV '.$nbvYear1] = '-';
            } 

            if($nbvYear2 >= $asset->purchased_date){
                $array['NBV '.$nbvYear2] = number_format((float)$asset->depreciation_value_by_date($nbvYear2), 2, '.', ',');
            }else{
                $array['NBV '.$nbvYear2] = '-';
            } 
            
            $costBFwd += $bf;
            $additions += $add;
            $disposals += $ac;
            $costCFwd += $cf;
            $depBFwd += $asset->purchased_cost - $bf;
            $depCharge += $bf - $cf;
            $depDisposal += 0;
            $depCFwd += $asset->purchased_cost - $cf;
            $nbv1 += $asset->depreciation_value_by_date($nbvYear1);
            $nbv2 += $asset->depreciation_value_by_date($nbvYear2);
            $object[] = $array;

            if($asset->archive_cost != null){
                $this->archived[] = $this->row
            }
            $this->row++;

        }
        $purchased_details = [];
        $purchased_details['Name'] = 'Total:  ' . $this->assets->count();
        $purchased_details['Purchased Cost'] = '';
        $purchased_details['Purchased Date'] = '';
        $purchased_details['Cost B/Fwd'] = number_format((float) $costBFwd, 2, '.', ',');
        $purchased_details['Additions'] = number_format((float) $additions, 2, '.', ',');
        $purchased_details['Disposals'] = number_format((float) $disposals, 2, '.', ',');
        $purchased_details['Cost C/Fwd'] = number_format((float) $costCFwd, 2, '.', ',');;
        $purchased_details['Depreciation B/Fwd'] = number_format((float) $depBFwd, 2, '.', ',');;
        $purchased_details['Depreciation Charge'] = number_format((float) $depCharge, 2, '.', ',');
        $purchased_details['Depreciation Disposal'] = number_format((float) $depDisposal, 2, '.', ',');
        $purchased_details['Depreciation C/Fwd'] = number_format((float) $depCFwd, 2, '.', ',');;
        $purchased_details['NBV '.$nbvYear1] = number_format((float) $nbv1, 2, '.', ',');
        $purchased_details['NBV '.$nbvYear2] = number_format((float) $nbv2, 2, '.', ',');
        array_push($object, $purchased_details);

        return $object;

    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $this->assets->count() + 2;
                $cellRange = 'A1:M1'; // All headers
                $cellRange2 = 'A' . $lastRow . ':M' . $lastRow; // Last Row
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12)->setBold(1);
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
