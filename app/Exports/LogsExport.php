<?php

namespace App\Exports;

use App\Models\log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class LogsExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents {

    public function headings(): array
    {
        return [
            "Data",
            "log_date",
            "loggable_type",
            "loggable_id",
            "updated_at",
        ];
    }

    public function array(): array
    {
        $logs = Log::all();
        $object = [];
        foreach($logs as $log)
        {
            $array = [];
            $array["Data"] = $log->data;
            $array["log_date"] = $log->log_date;
            $array["loggable_type"] = $log->loggable_type;
            $array["loggable_id"] = $log->loggable_id;
            $array["updated_at"] = $log->updated_at;
            $object[] = $array;


        }

        return $object;
    }

    //adds styles
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $cellRange = 'A1:E1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14)->setBold(1);
            },
        ];
    }

}
