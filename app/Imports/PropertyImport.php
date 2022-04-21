<?php

namespace App\Imports;

use App\Models\Property;
use App\Models\Location;

use App\Rules\permittedLocation;
use App\Rules\findLocation;
use Illuminate\Database\Eloquent\Model;
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
use Maatwebsite\Excel\Validators\Failure;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class PropertyImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

    /**
     * @param array     $row
     * @param Failure[] $failures
     * @return \Illuminate\Database\Eloquent\Model|null
     */
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
                'regex:/\d+(\.\d{1,2})?$/',
            ],
            'purchased_date' => [
                'date_format:"d/m/Y"',
            ],
            'type' => [],
            'location_id' => [
                'string',
                'required',
                new permittedLocation,
                new findLocation,
            ],
            'depreciation' => ['nullable'],

        ];


    }

    public function model(array $row)
    {

        $property = new Property;
        $property->name = $row["name"];
        switch($row['type'])
        {
            case 'Freehold Land':
                $type = 1;
                break;
            case 'Freehold Buildings':
                $type = 2;
                break;
            case 'Leasehold Land':
                $type = 3;
                break;
            case 'Leasehold Buildings':
                $type = 4;
                break;
            default:
                $type = 1;
        }
        $property->type = $type;
        $property->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");

        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $property->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $property->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        if(strtolower($row["donated"]) == 'yes')
        {
            $property->donated = 1;
        } else
        {
            $property->donated = 0;
        }

        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $property->location_id = $lid;

        $property->depreciation = $row["depreciation"];
        $property->user_id = auth()->user()->id;
        $property->save();
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
