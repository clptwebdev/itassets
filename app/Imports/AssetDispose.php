<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
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

class AssetDispose implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError {
{
    /**
     * @param array     $row
     * @param Failure[] $failures
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    use  importable, SkipsFailures, SkipsErrors;

    protected $asset;

    public function onError(\Throwable $error)
    {

    }

    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            foreach($validator->getData() as $key => $data)
            {
                    if($data['id'] != null && $asset = Asset::find($data['id'])){
                        /* If the user has added an ID (Unlikely) */
                        /* But trys to find it in this instance */
                        $this->asset = $asset;
                    }else{
                        /* If ID is not entered or an Asset is not found using the ID */
                        /* Location will then be required to try and find the Asset Tag and/or Serial No */
                        if($data['location_id'] != null && $location = Location::whereName($data['location_id'])){
                            if$asset = Asset::whereLocationId($location->id)->whereSerialNo($data['serial_no'])->orWhereAssetTag($data['asset_tag'])->first()){
                                $this->asset = $asset;
                            }
                        }
                    }
                    
                }
            }
            /* If the protected $asset is null it will return the following Error to the View */
            if($this->asset == null){
                $validator->errors()->add($key, 'More information is required in order to accuratley find the correct asset. Please makke su.');
            }
        });
    }

    public function rules(): array
    {
        //Asset Tag create rule to check to see if it exists in the same location [SC]
        return [
            'date' => [
                'date_format:"d/m/Y"',
                'required',
            ], 
            'reason' => [
                'required',
            ],
            'location_id' => [
                'required',
            ],
        ];


    }

    public function model(array $row)
    {
        $asset = $this->asset;
        /* If the Validation has been passed successfully */
        /* Create a new request to dispose this Asset [SC]*/
        $requests = Requests::create([
            'type'=>'disposal', 
            'model_type'=> 'asset', 
            'model_id'=>$asset->id,
            'notes' => $row['reason'],
            'date' => $row['date'],
            'user_id' => auth()->user()->id, 
            'status' => 0,
        ]);

        /* If the User is a Super Admin, we can skip the approval */
        /* Moves the Asset to the Archive [SC] */
        
        if(auth()->user()->role_id == 1){
            /* Get the Depreciaiton Years from the Asset Model or 0 */
            /* This is to calculate the Value of the Asset at the time of Disposal [SC] */
            $years = $asset->model->depreciation->years ?? 0
            $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($years);
            if($eol->isPast()){
                $dep = 0;
            }else{
                $age = \Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                $percent = 100 / $years;
                $percentage = floor($age)*$percent;
                $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
            }

            $archive = Archive::create([
                'model_type' => 'asset',
                'name' => $asset->name ?? 'No Name',
                'asset_tag' => $asset->asset_tag ?? 'No Asset Tag',
                'serial_no' => $asset->serial_no ?? 'N/A',
                'asset_model' => $asset->model->name ?? 'No Model',
                'order_no' => $asset->order_no ?? 'N/A',
                'supplier_id' => $asset->supplier_id ?? 0,
                'purchased_date' => $asset->purchased_date,
                'purchased_cost' => $asset->purchased_cost,
                'archived_cost' => number_format($dep, 2),
                'warranty' => $asset->warranty,
                'location_id' => $asset->location_id ?? 0,
                'room' => $asset->room ?? 'N/A',
                'logs' => 'N/A',
                'comments' => 'N/A',
                'created_user' => $asset->user_id ?? 0,
                'created_on' => $asset->created_at,
                'user_id' => auth()->user()->id,
                'super_id' => auth()->user()->id,
                'date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'notes' => $row['reason'],
            ]);
            $asset->forceDelete();
            $requests->update(['status' => 1, 'super_id'  => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
        }else{
            /* Send an Email to Super Admin requesting the Disposal */
        }

    }

    public function batchSize(): int
    {
        return 1000;
    }

}
