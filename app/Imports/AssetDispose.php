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
        //Asset Tag create rule to check to see if it exists in the same location
        return [
            'date' => [
                'sometimes',
                'nullable',
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
