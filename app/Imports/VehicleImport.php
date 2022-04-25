<?php

namespace App\Imports;

use App\Models\Location;
use App\Models\Software;
use App\Models\Supplier;
use App\Models\Vehicle;
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

class VehicleImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

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
            'registration' => [
                'required',
                'string',
            ],
            'purchased_cost' => ['required', 'regex:/^([^ b"])?(£)?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(\.[0-9][0-9])?(["])?$/'],
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
            'depreciation' => ['required'],
        ];


    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'You must provide a name to reference the Vehicle!',
            'registration.required' => 'Please enter the Vehicle Registration',
            'location_id.required' => 'Please assign the Vehicle to a Location',
            'purchased_cost.required' => 'The purchased cost for the Vehicle is empty!',
            'purchased_cost.regex' => 'The purchased cost is not in a valid format. Please enter a decmial currency without the £ symbol',
            'depreciation.required' => 'Please enter a depreciation value, this is a number of years',
            'depreciation.numeric' => 'The depreciation for the Vehicle is a number of years - the value is currently invalid',
            'purchased_date.required' => 'Please enter the date the Vehicle was purchased',
            'purchased_date.date_format' => 'An invalid date was entered for the Purchased Date, please follow the format: dd/mm/YYYY',
        ];
    }

    public function model(array $row)
    {
        $vehicle = new Vehicle;
        $vehicle->name = $row["name"];

        $vehicle->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");

        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $vehicle->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $vehicle->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $vehicle->location_id = $lid;

        //check for already existing Suppliers upon import if else create
        $supplier_email = 'info@' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
        if($supplier = Supplier::where(["name" => $row["supplier_id"]])->orWhere(['email' => $supplier_email])->first())
        {

        } else
        {
            if(isset($row["supplier_id"]) && $row["supplier_id"] != '')
            {
                $supplier = new Supplier;
                $supplier->name = $row["supplier_id"];
                $supplier->email = 'info@' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
                $supplier->url = 'www.' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
                $supplier->telephone = "Unknown";
                $supplier->save();
            }
        }
        $vehicle->supplier_id = $supplier->id ?? 0;
        $vehicle->order_no = $row["order_no"];

        $vehicle->depreciation = $row["depreciation"];
        $vehicle->registration = $row["registration"];

        $vehicle->save();
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
