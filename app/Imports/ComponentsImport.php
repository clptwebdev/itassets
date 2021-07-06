<?php

namespace App\Imports;

use App\Models\Component;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
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

class ComponentsImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError {

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
                'string',
            ],
            'purchased_date' => [
                'string',
            ],
            'supplier_id' => [
                'string',
            ],
            'location_id' => [

            ],
            'manufacturer_id' => [
                'string',
            ],

        ];


    }

    public function model(array $row)
    {
        $component = new Component;
        $component->name = $row["name"];

        $component->serial_no = $row["serial_no"];

        //check for already existing Status upon import if else create
        if($status = Status::where(["name" => $row["status_id"]])->first())
        {

        } else
        {
            $status = new Status;

            $status->name = $row["status_id"];
            $status->deployable = 1;

            $status->save();
        }
        $component->status_id = $status->id;

        $component->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        $component->purchased_cost = $row["purchased_cost"];

        //check for already existing Suppliers upon import if else create
        if($supplier = Supplier::where(["name" => $row["supplier_id"]])->first())
        {

        } else
        {
            $supplier = new Supplier;

            $supplier->name = $row["supplier_id"];
            $supplier->email = 'info@' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
            $supplier->url = 'www.' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
            $supplier->telephone = "Unknown";
            $supplier->save();
        }

        $component->supplier_id = $supplier->id;

        //check for already existing Manufacturers upon import if else create
        if($manufacturer = Manufacturer::where(["name" => $row["manufacturer_id"]])->first())
        {

        } else
        {
            $manufacturer = new Manufacturer;

            $manufacturer->name = $row["manufacturer_id"];
            $manufacturer->supportEmail = 'info@' . str_replace(' ', '', strtolower($row["manufacturer_id"])) . '.com';
            $manufacturer->supportUrl = 'www.' . str_replace(' ', '', strtolower($row["manufacturer_id"])) . '.com';
            $manufacturer->supportPhone = "Unknown";
            $manufacturer->save();
        }
        $component->manufacturer_id = $manufacturer->id;
        $component->order_no = $row["order_no"];
        $component->warranty = $row["warranty"];
        //check for already existing Locations upon import if else create
        if($location = Location::where(["name" => $row["location_id"]])->first())
        {

        } else
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
        }
        $component->location_id = $location->id;

        $component->notes = $row["notes"];
        $component->save();

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
