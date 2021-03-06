<?php

namespace App\Imports;

use App\Models\Accessory;
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
use App\Rules\checkAssetTag;

class accessoryImport extends DefaultValueBinder implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError, WithCustomValueBinder {

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
                'sometimes',
                'nullable',
            ],

            'purchased_cost' => [
                'required',
            ],
            'order_no' => [
                'nullable',
            ],
            'serial_no' => [
                'sometimes',
                'nullable',
            ],
            'notes' => [],
            'status_id' => [],
            'purchased_date' => [
                'date_format:"d/m/Y"',
            ],
            'supplier_id' => [],
            'location_id' => [
                'string',
                'required',
                new permittedLocation,
                new findLocation,
            ],
            "asset_tag" => ['sometimes', 'nullable', new checkAssetTag(':location_id')],
            'room' => ['nullable'],
            'manufacturer_id' => [],

        ];


    }

    public function model(array $row)
    {
        $accessory = new Accessory;
        $location = Location::where(["name" => $row["location_id"]])->first();
        $lid = $location->id ?? 0;
        $accessory->room = $row["room"];
        $accessory->location_id = $lid;

        $accessory->asset_tag = $row["asset_tag"];

        //Name of the Device cannot be null
        //If the device is NULL or empty - generate a name using initials of school and the ASSET Tag
        if($row["name"] != '')
        {
            $name = $row['name'];

        } else
        {
            $row['asset_tag'] != '' ? $tag = $row['asset_tag'] : $tag = '1234';
            $name = strtoupper(substr($location->name ?? 'UN', 0, 2)) . "-{$tag}";

        }
        $accessory->name = $name;

        $accessory->model = $row["model"];

        //Serial No Cannot be ""
        //If the imported Serial Number is empty assign it to "0"
        $row["serial_no"] != '' ? $accessory->serial_no = $row["serial_no"] : $accessory->serial_no = "-";

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
                $accessory->status_id = 0;
        }
        $accessory->status_id = $status->id ?? 0;

        $accessory->purchased_date = \Carbon\Carbon::parse(str_replace('/', '-', $row["purchased_date"]))->format("Y-m-d");
        if($this->isBinary($row["purchased_cost"]))
        {
            $binary = preg_replace('/[[:^print:]]/', '', $row['purchased_cost']);
            $accessory->purchased_cost = str_replace(',', '', $binary);
        } else
        {
            $accessory->purchased_cost = str_replace(',', '', $row["purchased_cost"]);
        }

        if(strtolower($row["donated"]) == 'yes')
        {
            $accessory->donated = 1;
        }

        //check for already existing Suppliers upon import if else create
        $supplier_email = 'info@' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
        if($supplier = Supplier::where(["name" => $row["supplier_id"]])->orWhere(['email' => $supplier_email])->first())
        {

        } else
        {
            if(isset($row["supplier_id"]))
            {
                $supplier = new Supplier;

                $supplier->name = $row["supplier_id"];
                $supplier->email = $supplier_email;
                $supplier->url = 'www.' . str_replace(' ', '', strtolower($row["supplier_id"])) . '.com';
                $supplier->telephone = "Unknown";
                $supplier->save();

            } else
                $accessory->supplier_id = 0;
        }

        $accessory->supplier_id = $supplier->id ?? 0;

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

        $accessory->manufacturer_id = $manufacturer->id ?? 0;

        $accessory->order_no = $row["order_no"];
        $accessory->warranty = $row["warranty"];

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

        $depreciation = Depreciation::where(["name" => $row["depreciation_id"]])->first();
        $id = $depreciation->id ?? 0;
        $accessory->depreciation_id = $id;

        $accessory->photo_id = 0;

        $accessory->notes = $row["notes"];
        $accessory->user_id = auth()->user()->id;
        $accessory->save();
        if(isset($cat_array))
        {
            $accessory->category()->attach($cat_array);
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
