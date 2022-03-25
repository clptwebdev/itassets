<?php

namespace App\Exports;

use App\Models\Asset;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class AssetExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    use Exportable;

    private $assets;

    public function __construct($assets)
    {
        $this->assets = $assets;
    }

    public function headings(): array
    {
        return [
            "asset_tag",
            "name",
            "serial_no",
            "asset_model",
            "status_id",
            "purchased_date",
            "purchased_cost",
            "supplier_id",
            "manufacturer_id"
            , "order_no"
            , "warranty"
            , "location_id"
            , "user_id"
            , "audit_date",
        ];
    }

    public function array(): array
    {
        $object = [];
        foreach($this->assets as $asset)
        {
            $array = [];
            $array['asset_tag'] = $asset->asset_tag;
            $array['name'] = $asset->name;
            $array['serial_no'] = $asset->serial_no ?? 'Unknown';
            $array['asset_model'] = $asset->model->name ?? 'Unknown';
            $array['status_id'] = $asset->status->name ?? 'Unknown';
            $array['purchased_date'] = $asset->purchased_date ?? 'Unknown';
            $array['purchased_cost'] = $asset->purchased_cost ?? 'Unknown';
            $array['supplier_id'] = $asset->supplier->name ?? 'Unknown';
            $array['manufacturer_id'] = $asset->model->manufacturer->name ?? 'Unknown';
            $array['order_no'] = $asset->order_no ?? 'Unknown';
            $array['warranty'] = $asset->warranty ?? 'Unknown';
            $array['location_id'] = $asset->location->name ?? 'Unknown';
            $array['user_id'] = $asset->user->name ?? 'Unknown';
            $array['audit_date'] = $asset->audit_date ?? 'Unknown';
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
