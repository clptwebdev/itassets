<?php

namespace App\Imports;

use App\Models\Component;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class ComponentsImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError {

    /**
     * @param array     $row
     * @param Failure[] $failures
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use  importable, SkipsFailures, SkipsErrors;

    public function onError(\Throwable $error)
    {

    }

    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',

            ],
            'purchased_cost' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'order_no' => [
                'required',
                "string",

            ],
            'serial_no' => [
                'required',
                "string",
            ],
            'warranty' => [
                'int',
            ],

        ];


    }

    public function model(array $row)
    {
        return new Component([
            'name' => $row["name"],
            'serial_no' => $row["serial_no"],
            'status_id' => $row["status_id"],
            'purchased_date' => \Carbon\Carbon::parse($row["purchased_date"])->format("Y-m-d") ,
            'purchased_cost' => $row["purchased_cost"],
            'supplier_id' => $row["supplier_id"],
            'manufacturer_id' => $row["manufacturer_id"],
            'order_no' => $row["order_no"],
            'warranty' => $row["warranty"],
            'location_id' => $row["location_id"],
            'notes' => $row["notes"],

        ]);

    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function uniqueBy()
    {
        return 'name';
    }

}
