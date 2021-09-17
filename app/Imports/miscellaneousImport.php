<?php

namespace App\Imports;

use App\Models\Miscellanea;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
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

class miscellaneousImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError {

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
        $miscellanea->purchased_cost = $row["purchased_cost"];

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

        $miscellanea->supplier_id = $supplier->id ?? 0;

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
        $miscellanea->photo_id =  0;
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

}
