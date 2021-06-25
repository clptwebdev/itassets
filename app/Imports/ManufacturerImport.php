<?php

namespace App\Imports;

use App\Models\Manufacturer;
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

class ManufacturerImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts,SkipsOnFailure,SkipsOnError{

    /**
     * @param array     $row
     * @param Failure[] $failures
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use  importable ,SkipsFailures ,SkipsErrors;
        public function onError(\Throwable $error)
    {

    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                "unique:manufacturers,name",
            ],
            'supporturl' => [
                'required',
                'string',
            ],
            'supportphone' => [
                'required',
                'max:14',
                'string',
            ],
            'supportemail' => [
                'required',
                'email:rfc,dns,spoof,filter',
                'unique:manufacturers,supportEmail',
            ],
        ];
    }

    public function model(array $row)
    {
        return new Manufacturer([
            'name' => $row["name"],
            'supportUrl' => $row["supporturl"],
            'supportPhone' => $row["supportphone"],
            'supportEmail' => $row["supportemail"],

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

