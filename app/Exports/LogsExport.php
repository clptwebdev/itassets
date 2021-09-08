<?php

namespace App\Exports;

use App\Models\log;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LogsExport implements FromArray, WithHeadings {

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

}
