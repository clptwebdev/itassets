<?php

namespace App\Exports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Events\AfterSheet;

class BusinessExport implements WithMultipleSheets {

    use Exportable;

    public function __construct($computers, $property, $ffe, $auc, $machines, $vehicle)
    {
        $this->computers = $computers;
        $this->property = $property;
        $this->ffe = $ffe;
        $this->auc = $auc;
        $this->machines = $machines;
        $this->vehicle = $vehicle;
    }

    public function sheets(): array
    {
        $sheets = [];

        for($model = 1; $model <= 1; $model++)
        {
            $sheets[] = new FFEExport($this->ffe);
            $sheets[] = new AUCExport($this->auc);
            $sheets[] = new PropertyExport($this->property);
            $sheets[] = new MachineryExport($this->machines);
            $sheets[] = new VehicleExport($this->vehicle);
            $sheets[] = new ComputerExport($this->computers);
        }

        return $sheets;
    }

}
