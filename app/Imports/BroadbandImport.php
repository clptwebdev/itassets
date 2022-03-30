<?php

namespace App\Imports;

use App\Models\Broadband;
use App\Models\Location;
use App\Models\Software;
use App\Models\Supplier;
use App\Rules\findLocation;
use App\Rules\permittedLocation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class BroadbandImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

    use  importable, SkipsFailures, SkipsErrors;

    public function onError(\Throwable $error)
    {

    }

    public function bindValue(Cell|\PhpOffice\PhpSpreadsheet\Cell\Cell $cell, $value)
    {

        $cell->setValueExplicit($value, DataType::TYPE_FORMULA);

        return true;

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
            ],
            'purchased_date' => [
                'date_format:"d/m/Y"',

            ],
            'supplier_id' => [
                'required',
            ],
            'location_id' => [
                'string',
                'required',
                new permittedLocation,
                new findLocation,
            ],
            'renewal_date' => ['required'],
            'package' => ['required'],

        ];


    }

    public function model(array $row)
    {

        $broadband = new Broadband;
        $broadband->name = $row["name"];

        $broadband->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $broadband->purchased_cost = floatval($binary);
        } else
        {
            $broadband->purchased_cost = floatval($row["purchased_cost"]);
        }

        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $broadband->location_id = $lid;
        $supplier = Supplier::where(["name" => $row["supplier_id"]])->first();
        $sid = $supplier->id ?? 0;
        $broadband->supplier_id = $sid;
        $broadband->renewal_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["renewal_date"]))->format("Y-m-d");
        $broadband->package = $row['package'];
        $broadband->save();
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function uniqueBy()
    {
        return 'name';
    }

    function isBinary($str)
    {
        return preg_match('~[^\x20-\x7E\t\r\n]~', $str) > 0;
    }

}
