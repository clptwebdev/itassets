<?php

namespace App\Imports;

use App\Models\Depreciation;
use App\Models\Miscellanea;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
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

class miscellaneousImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

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
            ],
            'order_no' => [
                'required',

            ],
            'serial_no' => [
                'required',
            ],
            'notes' => [

            ],
            'status_id' => [

            ],
            'purchased_date' => [
                'string',
            ],
            'supplier_id' => [
                'string',
                'required',
            ],
            'location_id' => [
                'string',
                'required',
            ],
            'manufacturer_id' => [

            ],

        ];


    }

    public function model(array $row)
    {

        $miscellanea = new Miscellanea;
        $miscellanea->name = $row["name"];
        $miscellanea->room = $row["room"];

        $miscellanea->serial_no = $row["serial_no"];

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
            } else
                $miscellanea->status_id = 0;
        }
        $miscellanea->status_id = $status->id ?? 0;

        $miscellanea->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $miscellanea->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $miscellanea->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        //check for already existing Suppliers upon import if else create
        if($supplier = Supplier::where(["name" => $row["supplier_id"]])->first())
        {

        } else
        {
            if(isset($row["supplier_id"]))
            {
                $supplier = new Supplier;

                $supplier->name = $row["supplier_id"];
                $supplier->email = 'info@' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
                $supplier->url = 'www.' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
                $supplier->telephone = "Unknown";
                $supplier->save();

            } else
                $miscellanea->supplier_id = 0;
        }
        if($depreciation = Depreciation::where(["name" => $row["depreciation_id"]])->first())
        {

        } else
        {
            if(isset($row["depreciation_id"]))
            {
                $depreciation = new Depreciation();

                $depreciation->name = $row["depreciation_id"];
                $depreciation->years = 3;
                $depreciation->save();

            } else
                $miscellanea->depreciation_id = 0;
        }

        $miscellanea->depreciation_id = $depreciation->id ?? 0;

        //check for already existing Manufacturers upon import if else create
        if($manufacturer = Manufacturer::where(["name" => $row["manufacturer_id"]])->first())
        {

        } else
        {
            if(isset($row["manufacturer_id"]))
            {
                $manufacturer = new Manufacturer;

                $manufacturer->name = $row["manufacturer_id"];
                $manufacturer->supportEmail = 'info@' . str_replace(' ', '', strtolower($row["manufacturer_id"])) . '.com';
                $manufacturer->supportUrl = 'www.' . str_replace(' ', '', strtolower($row["manufacturer_id"])) . '.com';
                $manufacturer->supportPhone = "Unknown";
                $manufacturer->save();
            } else
                $miscellanea->supplier_id = 0;

        }
        $miscellanea->manufacturer_id = $manufacturer->id ?? 0;
        $miscellanea->photo_id = 0;
        $miscellanea->order_no = $row["order_no"];
        $miscellanea->warranty = $row["warranty"];
        //check for already existing Locations upon import if else create
        if($location = Location::where(["name" => $row["location_id"]])->first())
        {

        } else
        {
            if(isset($row["location_id"]))
            {
                $location = new Location;

                $location->name = $row["location_id"];
                $location->email = 'enquiries@' . str_replace(' ', '', strtolower($row["location_id"])) . '.co.uk';
                $location->telephone = "01902556360";
                $location->address_1 = "Unknown";
                $location->city = "Unknown";
                $location->postcode = "Unknown";
                $location->county = "West Midlands";
                $location->icon = "#222222";
                $location->save();
            } else
                $miscellanea->location_id = 0;
        }
        $miscellanea->location_id = $location->id ?? 0;
        $miscellanea->photo_id = 0;
        $miscellanea->notes = $row["notes"];
        $miscellanea->save();
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

