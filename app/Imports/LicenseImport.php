<?php

namespace App\Imports;

use App\Models\Broadband;
use App\Models\License;
use App\Models\Location;
use App\Models\Supplier;
use App\Rules\findLocation;
use App\Rules\permittedLocation;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class LicenseImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

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
                'string',
            ],
            'expiry' => [
                'nullable',
            ],
            'purchased_cost' => [
                'nullable',
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
            'contact' => [
                'nullable',
                'email:rfc,dns,filter',
            ],

        ];


    }

    public function model(array $row)
    {

        $license = new License;
        $license->name = $row["name"];

        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $license->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $license->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $license->location_id = $lid;
        $supplier = Supplier::where(["name" => $row["supplier_id"]])->first();
        $sid = $supplier->id ?? 0;
        $license->supplier_id = $sid;
        $license->expiry = \Carbon\Carbon::parse(str_replace('/', '-', $row["expiry"]));
        $license->contact = $row['contact'];
        $license->save();
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
