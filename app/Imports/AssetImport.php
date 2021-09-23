<?php

namespace App\Imports;

use App\Models\Asset;
use App\Models\AssetModel;
use App\Models\Location;
use App\Models\Manufacturer;
use App\Models\Status;
use App\Models\Category;
use App\Models\Field;
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
use function PHPUnit\Framework\isEmpty;

class AssetImport implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError {

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
                'sometimes',
                'nullable',
                'unique:assets',
            ],'name' => [
                'required',
                'string',
            ],
            'purchased_cost' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'order_no' => [
                'nullable',
            ],
            'serial_no' => [
                'required',
            ],
            'purchased_date' => [
                'date_format:"d/m/Y"',
            ],
            'audit_date' => [
                'date_format:"d/m/Y"'
            ],
            'supplier_id' => [
            ],
            'location_id' => [
            ]
            , 'status_id' => [
            ],
        ];


    }

    public function model(array $row)
    {

            $asset = new Asset;
            $asset->asset_tag = $row["asset_tag"];
            $asset->name = $row["name"];
            $asset->user_id = auth()->user()->id;
            $asset->serial_no = $row["serial_no"];

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
                    $asset->status_id = 0;
            }
            $asset->status_id = $status->id ?? 0;

            $asset->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
            $asset->purchased_cost = $row["purchased_cost"];

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
                    $asset->supplier_id = 0;

            }
            $asset->supplier_id = $supplier->id ?? 0;
            //check for already existing Manufacturers upon import if else create
            $asset->order_no = $row["order_no"];
            $asset->warranty = $row["warranty"];
            //check for already existing Locations upon import if else create if blank dont assign it to a location
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
                    $asset->location_id = 0;

            }
            $asset->location_id = $location->id ?? 0;

            if($asset_model = AssetModel::where(["name" => $row["asset_model_id"]])->first())
            {
                $asset->asset_model = $asset_model->id;
            } else
            {
                $asset->asset_model = 0;
            }
            if($row["audit_date"] === null??0){
                $asset->audit_date = null;
            }else{
                $asset->audit_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["audit_date"]))->format("Y-m-d");

            }

            if(isset($row['categories'])){
                $cat_array = array();
                $categories = explode(',', $row['categories']);
                foreach($categories as $category){
                    $found = Category::firstOrCreate(['name' => $category]);
                    $cat_array[] = $found->id;
                }
            }

            if(isset($row['additional'])){
                $additional = array();
                $fields = explode(';', $row['additional']);
                foreach($fields as $field){
                    $field_value = explode(':', $field);
                    if($found = Field::where(['name' => $field_value[0]])->first()){
                        return dd($found);
                        $additional[$found->id] = ['value' => $field_value[1]];
                    }
                }
            }

            


            $asset->save();
            if(isset($cat_array)){
                $asset->category()->attach($cat_array);
            }
            if(isset($additional)){
                $asset->fields()->attach($additional);
            }

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
