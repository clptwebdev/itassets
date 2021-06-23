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
//    public function onFailure(Failure ...$failures)
//    {
//       return $failures;
//    }
//    public function onFailure(Failure ...$failures)
//    {
//        $results = [];
//        foreach($failures as $failure)
//        {
//
//            $results[] = [
//                'row' => $failure->row(),
//                'attributes' => $failure->attribute(),
//                'errors' => $failure->errors(),
//                'value' => $failure->values(),
//            ];
//
//        }
//        return ($results);
//    }

    public function rules(): array
    {

        return [
            'name' => [
                'required',
                'string',
            ],
            'supporturl' => [
                'required',
                'string',
            ],
            'supportphone' => [
                'required',
                'string',
            ],
            'supportemail' => [
                'required',
                'email:rfc,dns,spoof,filter',
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

