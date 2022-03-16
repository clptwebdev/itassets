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
use App\Rules\permittedLocation;
use App\Rules\findLocation;
use App\Rules\checkAssetTag;
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
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Validators\Failure;
use phpDocumentor\Reflection\Types\False_;
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

    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            foreach($validator->getData() as $key => $data)
            {
                if($data['asset_tag'] != null) {
                    $location = Location::where('name', "=", $data['location_id'])->first();
                    if($asset = Asset::where("asset_tag", '=', $data['asset_tag'])->where('location_id', '=', $location->id)->first()){
                            $validator->errors()->add($key.'.asset_tag', 'This Asset Tag is already assigned in this location.');
                    }
                    
                }
            }
        });
    }

    public function rules(): array
    {
        //Asset Tag create rule to check to see if it exists in the same location
        return [
            'asset_tag' => [
                'sometimes',
                'nullable',

            ], 'name' => [
                'nullable'
            ],
            'purchased_cost' => [
                'required',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'order_no' => [
                'nullable',
            ],
            'serial_no' => [
                'nullable'
            ],
            'purchased_date' => [
                'date_format:"d/m/Y"',
            ],
            'audit_date' => [
                'date_format:"d/m/Y"',
            ],
            'supplier_id' => [
            ],
            'location_id' => [
                'string',
                'required',
                new permittedLocation,
                new findLocation,
            ]
            , 'status_id' => [
            ],
        ];


    }

    public function model(array $row)
    {
        $asset = new Asset;

        //check for already existing Locations upon import if else create if blank dont assign it to a location
        $location = Location::where(["name" => $row["location_id"]])->first();
        $id = $location->id ?? 0;
        $asset->location_id = $id;
        $asset->room = $row['room'];

        $asset->asset_tag = $row["asset_tag"];

        //Name of the Device cannot be null
        //If the device is NULL or empty - generate a name using initials of school and the ASSET Tag
        if($row["name"] != ''){
            $name = $row['name'];
        }else{
            $row['asset_tag'] != '' ? $tag = $row['asset_tag'] : $tag = '1234'; 
            $name = strtoupper(substr($asset->location->name ?? 'UN', 0, 1))."-{$tag}";
        }
        $asset->name = $name;


        $asset->user_id = auth()->user()->id;

        //Serial No Cannot be ""
        //If the imported Serial Number is empty assign it to "0"
        $row["serial_no"] != '' ? $asset->serial_no = $row["serial_no"] : $asset->serial_no = "-" ;

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
            {
                $asset->status_id = 0;
            }
        }

        $asset->status_id = $status->id ?? 0;

        $asset->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        $asset->purchased_cost = $row["purchased_cost"];
        if(strtolower($row["donated"]) == 'yes')
        {
            $asset->donated = 1;
        } else
        {
            $asset->donated = 0;
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
            {
                $asset->supplier_id = 0;
            }
        }
        $asset->supplier_id = $supplier->id ?? 0;
        //check for already existing Manufacturers upon import if else create
        $asset->order_no = $row["order_no"];
        $asset->warranty = $row["warranty"] ?? 0;
        //check for already existing Locations upon import if else create if blank dont assign it to a location
        $location = Location::where(["name" => $row["location_id"]])->first();
        $id = $location->id ?? 0;
        $asset->location_id = $id;
        $asset->room = $row['room'];

        if($asset_model = AssetModel::where(["name" => $row["asset_model"]])->first())
        {
            $asset->asset_model = $asset_model->id;
            $additional = array();
            if($asset_model->fieldset()->exists())
            {
                foreach($asset_model->fieldset->fields as $field)
                {
                    if(array_key_exists(str_replace(' ', '_', strtolower($field->name)), $row) && $row[str_replace(' ', '_', strtolower($field->name))] != null)
                    {
                        $additional[$field->id] = ['value' => $row[str_replace(' ', '_', strtolower($field->name))]];
                    }
                }
            }
        } elseif($row["asset_model"] != ''){
            $model = new AssetModel;
            $model->name = $row["asset_model"];
            $model->model_no = 'Unknown';
            $model->fieldset_id = 1;
            $model->save();
            $asset->asset_model = $model->id;
        }else{
            $asset->asset_model = 0;
        }

        $asset->notes = $row['notes'];

        if($row["audit_date"] === null ?? 0)
        {
            $asset->audit_date = null;
        } else
        {
            $asset->audit_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["audit_date"]))->format("Y-m-d");
        }

        if(isset($row['categories']))
        {
            $cat_array = array();
            $categories = explode(',', $row['categories']);
            foreach($categories as $category)
            {
                $found = Category::firstOrCreate(['name' => $category]);
                $cat_array[] = $found->id;
            }
        }

        $asset->save();
        if(isset($cat_array))
        {
            $asset->category()->attach($cat_array);
        }
        if(isset($additional))
        {
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
