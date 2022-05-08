<?php

namespace App\Imports;

use App\Models\AUC;
use App\Models\Location;
use App\Models\Software;
use App\Models\Supplier;
use App\Models\Manufacturer;
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

class SoftwareImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

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
                'regex:/^([^ b"])?(£)?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(\.[0-9][0-9])?(["])?$/'
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
            'depreciation' => ['nullable'],

        ];


    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'You must provide a name to reference the Software!',
            'location_id.required' => 'Please assign the Software to a Location',
            'purchased_cost.required' => 'The purchased cost for the Software is empty!',
            'purchased_cost.regex' => 'The purchased cost is not in a valid format. Please enter a decmial currency without the £ symbol',
            'depreciation.required' => 'Please enter a depreciation value, this is a number of years',
            'depreciation.numeric' => 'The depreciation for the Software is a number of years - the value is currently invalid',
            'purchased_date.required' => 'Please enter the date the Software was purchased',
            'purchased_date.date_format' => 'An invalid date was entered for the Purchased Date, please follow the format: dd/mm/YYYY',
        ];
    }

    public function model(array $row)
    {

        $software = new Software;
        $software->name = $row["name"];

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
        $software->supplier_id = $supplier->id ?? 0;
        
        //check for already existing Manufacturers upon import if else create
        $man_email = 'info@' . str_replace(' ', '', strtolower($row["manufacturer_id"])) . '.com';
        if($manufacturer = Manufacturer::where(["name" => $row["manufacturer_id"]])->orWhere(['supportEmail' => $supplier_email])->first())
        {

        } else
        {
            if(isset($row["manufacturer_id"]))
            {
                $manufacturer = new Manufacturer;

                $manufacturer->name = $row["manufacturer_id"];
                $manufacturer->supportEmail = $man_email;
                $manufacturer->supportUrl = 'www.' . str_replace(' ', '', strtolower($row["manufacturer_id"])) . '.com';
                $manufacturer->supportPhone = "Unknown";
                $manufacturer->save();
            }
        }
        $software->manufacturer_id = $manufacturer->id ?? 0;

        $software->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $software->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $software->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        //Donated FFE
        if(strtolower($row["donated"]) == 'yes')
        {
            $software->donated = 1;
        } else
        {
            $software->donated = 0;
        }

        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $software->location_id = $lid;
        
        $software->warranty = $row["warranty"];
        $software->order_no = $row["order_no"];
        $software->depreciation = $row["depreciation"];

        $software->save();
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
