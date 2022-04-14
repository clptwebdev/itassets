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

    public function __construct($computers, $property, $ffe, $auc, $machinery, $vehicles, $softwares)
    {
        $this->computers = $computers;
        $this->property = $property;
        $this->ffe = $ffe;
        $this->auc = $auc;
        $this->machinery = $machinery;
        $this->vehicles = $vehicles;
        $this->softwares = $softwares;

    }

    public function sheets(): array
    {
        $sheets = [];

        for($model = 1; $model <= 1; $model++)
        {
//            $sheets[] = new PropertyBusinessExport($this->property);
//            $sheets[] = new AUCBusinessExport($this->auc);
//            $sheets[] = new FFEBusinessExport($this->ffe);
//            $sheets[] = new MachineryBusinessExport($this->machinery);
//            $sheets[] = new VehicleBusinessExport($this->vehicles);
            $sheets[] = new ComputerExport($this->computers);
//            $sheets[] = new SoftwareBusinessExport($this->softwares);
        }

        return $sheets;
    }

}
