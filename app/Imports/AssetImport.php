<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
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

class AssetImport implements ToModel , WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError
{
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
            'asset_tag' => [
                'required',
                'unique:assets'
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
            'status_id' => [
                'string',
            ],
            'purchased_date' => [
                'string',
            ],
            'audit_date' => [
                'string',
            ],
            'supplier_id' => [
                'string',
            ],
            'location_id' => [
            ]
        ];


    }


    public function model(array $row)
    {

        $asset = new Asset;
        $asset->asset_tag = $row["asset_tag"];

        $asset->serial_no = $row["serial_no"];

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
        $asset->status_id = $status->id;

        $asset->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        $asset->purchased_cost = $row["purchased_cost"];

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

        $asset->supplier_id = $supplier->id;

        //check for already existing Manufacturers upon import if else create
        $asset->order_no = $row["order_no"];
        $asset->warranty = $row["warranty"];
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
        $asset->location_id = $location->id;

        if($asset_model = AssetModel::where(["name" => $row["asset_model_id"]])->first())
        {
            $asset->asset_model = $asset_model->id;
        } else
        {
            $asset->asset_model = 0;
        }


        $asset->save();
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function uniqueBy()
    {
        return 'asset_tag';
    }

}
