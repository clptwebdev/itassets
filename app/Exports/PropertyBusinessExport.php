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
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
use Maatwebsite\Excel\Sheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PropertyBusinessExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents, WithTitle, WithColumnFormatting {

    use Exportable;

    private $properties;
    private $now;
    private $startDate;
    private $nextYear;
    private $nextStartDate;
    private $endDate;
    private $nbvYear1;
    private $nbvYear2;

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'D' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'E' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'G' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'H' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'I' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'K' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'L' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'M' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,
            'N' => NumberFormat::FORMAT_CURRENCY_GBP_SIMPLE,

        ];
    }

    public function __construct($properties)
    {
        $this->properties = $properties;
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
    }

    public function headings(): array
    {
        return [
            "Name",
            "Cost",
            "Date",
            "Cost B/Fwd (" . $this->startDate->format('d\/m\/Y') . ")",
            "Additions",
            "Disposals",
            "Cost C/Fwd (" . $this->endDate->format('d\/m\/Y') . ")",
            "Depn B/Fwd (" . $this->startDate->format('d\/m\/Y') . ")",
            "Depn Charge",
            "Depn Disposal",
            "Depn C/Fwd (" . $this->endDate->format('d\/m\/Y') . ")",
            "NBV (" . $this->nbvYear1->format('Y') . ")",
            "NBV (" . $this->nbvYear2->format('Y') . ")",
        ];
    }

    public function array(): array
    {
        $properties = $this->properties;

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

        foreach($properties as $property)
        {
            $bf = $property->depreciation_value_by_date($startDate);
            $cf = $property->depreciation_value_by_date($nextStartDate);

            $depEndDate = 0;
            $monthsStart = 0;
            $monthsCharged = 0;
            $monthsEnd = 0;

            $array = [];
            $array['Name'] = $property->name;
            $array['Purchased Cost'] = $property->purchased_cost;

            $purchased_date = Carbon::parse($property->purchased_date);
            $array['Purchased Date'] = $purchased_date->format('d\/m\/Y') ?? '-';

            $array['Cost B/Fwd'] = $bf ?? '0.00';
            $purchased_date > $startDate ? $add = $property->purchased_cost : $add = 0;
            $array['Additions'] = $add ?? '-';
            $property->archived_cost ? $ac = $property->archived_cost : $ac = '0';
            $array['Disposals'] = $ac ?? '-';
            $array['Cost C/Fwd'] = $cf ?? '0.00';
            $array['Depn B/Fwd'] = $property->purchased_cost - $bf ?? '0.00';
            $array['Depn Charge'] = $bf - $cf ?? '-';
            $array['Depn Disposal'] = '-';
            $array['Depn C/Fwd'] = $property->purchased_cost - $cf ?? '0.00';

            if($nbvYear1 >= $property->purchased_date)
            {
                $array['NBV ' . $nbvYear1] = $property->depreciation_value_by_date($nbvYear1);
            } else
            {
                $array['NBV ' . $nbvYear1] = '-';
            }

            if($nbvYear2 >= $property->purchased_date)
            {
                $array['NBV ' . $nbvYear2] = $property->depreciation_value_by_date($nbvYear2);
            } else
            {
                $array['NBV ' . $nbvYear2] = '-';
            }

            $costBFwd += $bf;
            $additions += $add;
            $disposals += $ac;
            $costCFwd += $cf;
            $depBFwd += $property->purchased_cost - $bf;
            $depCharge += $bf - $cf;
            $depDisposal += 0;
            $depCFwd += $property->purchased_cost - $cf;
            $nbv1 += $property->depreciation_value_by_date($nbvYear1);
            $nbv2 += $property->depreciation_value_by_date($nbvYear2);
            $object[] = $array;

        }
        $purchased_details = [];
        $purchased_details['Name'] = 'Total:  ' . $this->properties->count();
        $purchased_details['Purchased Cost'] = '';
        $purchased_details['Purchased Date'] = '';
        $purchased_details['Cost B/Fwd'] = $costBFwd;
        $purchased_details['Additions'] = $additions;
        $purchased_details['Disposals'] = $disposals;
        $purchased_details['Cost C/Fwd'] = $costCFwd;
        $purchased_details['Depreciation B/Fwd'] = $depBFwd;
        $purchased_details['Depreciation Charge'] = $depCharge;
        $purchased_details['Depreciation Disposal'] = $depDisposal;
        $purchased_details['Depreciation C/Fwd'] = $depCFwd;
        $purchased_details['NBV ' . $nbvYear1] = $nbv1;
        $purchased_details['NBV ' . $nbvYear2] = $nbv2;
        array_push($object, $purchased_details);

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $this->properties->count() + 2;
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
        return 'Property';
    }

}
