<?php

namespace App\Imports;

use App\Models\FFE;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use App\Models\Category;
use App\Models\Depreciation;

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

class FFEImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

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
            'location_id' => [
                'string',
                'required',
                new permittedLocation,
                new findLocation,
            ],
            'depreciation_id' => ['nullable'],

        ];


    }

    public function model(array $row)
    {

        try{

        $ffe = new FFE;
        $ffe->name = $row["name"];
        //Serial No
        //If Serial No is empty enter '-'
        $row["serial_no"] != '' ? $ffe->serial_no = $row["serial_no"] : $ffe->serial_no = '-';
        //check for already existing Status upon import if else create
        if($status = Status::where(["name" => $row["status_id"]])->first())
        {

        } else
        {
            if(isset($row["status_id"]))
            {
                $status = new Status;
                $status->name = $row["status_id"];
                $status->deployable = 1;
                $status->save();
            }
        }

        $ffe->status_id = $status->id ?? 0;

        //Purchased Date into tje Correct format
        $ffe->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        //This function allows the purchased cost to be parsed with e '£' symbol and or as currency from the excel spreadsheet
        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $ffe->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $ffe->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        //Donated FFE
        if(strtolower($row["donated"]) == 'yes')
        {
            $ffe->donated = 1;
        } else
        {
            $ffe->donated = 0;
        }

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

        $ffe->supplier_id = $supplier->id ?? 0;
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
        $ffe->manufacturer_id = $manufacturer->id ?? 0;
        $ffe->depreciation = $row["depreciation"];
        $ffe->warranty = $row["warranty"];
        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $ffe->location_id = $lid;
        $ffe->room = $row['room'];
        $ffe->notes = $row['notes'];
        $ffe->user_id = auth()->user()->id;
        $ffe->save();
        }catch(\Exception $e){
            return dd($e->getMessage());
        }
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
