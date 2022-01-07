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

use App\Models\Asset;
use App\Models\Location;
use App\Models\Requests;
use App\Models\Transfer;

class AssetTransfer implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError
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

    public function withValidator($validator)
    {
        $validator->after(function($validator) {
            foreach($validator->getData() as $key => $data)
            {
                if($data['id'] != null && $asset = Asset::find($data['id'])){
                    /* If the user has added an ID (Unlikely) */
                    /* But trys to find it in this instance */
                    $validator->errors()->add($key.'.id', 'An Asset could not be found using the entered details');
                }else{
                    /* Find where the Asset is Located */
                    if($data['location_to'] != null){
                        $location_to = Location::whereName($data['location_to'])->first();
                    }else{
                        $validator->errors()->add($key.'.location_to', 'Please enter where the Asset is currently Located!');
                    }

                    /* Find where the Asset is to be moved to */
                    if($data['location_from'] != null){
                        $location_from = Location::whereName($data['location_from'])->first();
                    }else{
                        $validator->errors()->add($key.'.location_to', 'Please enter where the Asset is to be transferred to!');
                    }
                    /* If the Location to and from exist */
                    if(isset($location_to) && isset($location_from)){
                        if($data['serial_no'] == null && $data['asset_tag'] == null){
                            /* If Serial No and Asset Tag are both null then there is not enough data to accuratly find the Asset so error returned */
                            $validator->errors()->add($key.'.serial_no', 'Please enter the Serial No and/or the Asset Tag - there is not enough information to locate the Asset');
                            $validator->errors()->add($key.'.asset_tag', 'Please enter the Serial No and/or the Asset Tag - there is not enough information to locate the Asset'); 
                        }else{
                            /* If the Asset has a new Asset tag, then check to see if there isnt already an asset with the same tag at that location */
                            $count = Asset::where('location_id', '=', $location_to->id);
                            if($data['new_tag'] !== ''){
                                $count->where('asset_tag', '=', $data['new_tag']);
                            }
                            if($data['new_tag'] == '' || $count->count() == 0){
                                /* Check to see if the Asset can be found */
                                $asset = Asset::where('location_id', '=', $location_from->id);
                                if($data['serial_no'] != ''){
                                    $asset->where('serial_no', '=', $data['serial_no']);
                                }
                                if($data['asset_tag'] != ''){
                                    $asset->where('asset_tag', '=', $data['asset_tag']);
                                }
                                if($asset->first() == null){
                                    /* Asset cant be found */
                                    $validator->errors()->add($key.'.asset_tag', 'An Asset could not be found using the entered details');
                                }
                            }else{
                                /* Asset Tag for the new location exists */
                                $validator->errors()->add($key.'.new_tag', 'An Asset with the New Tag already exists for this Location');
                            }
                            
                        }
                    }else{
                        /* Cant find one of or either of the Locations */
                        $validator->errors()->add($key.'.location_id', 'There Current Location or the Location where there Asset is to be transferred, could not be found');
                    }
                }
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
            'notes' => [
                'required',
            ],
            'location_to' => [
                'required',
            ],
            'location_to' => [
                'required',
            ],
            'location_to' => [
                'required',
            ],
        ];


    }

    public function model(array $data)
    {
        
        $location_from = Location::whereName($data['location_from'])->first();
        $location_to = Location::whereName($data['location_to'])->first();

        $find = Asset::where('location_id', '=', $location_from->id);
        if($data['serial_no'] != ''){
            $find->where('serial_no', '=', $data['serial_no']);
        }
        if($data['asset_tag'] != ''){
            $find->where('asset_tag', '=', $data['asset_tag']);
        }
        $asset = $find->first();
        /* If the Validation has been passed successfully */
        /* Create a new request to dispose this Asset [SC]*/
        if($asset){
            $requests = Requests::create([
                'type'=>'transfer', 
                'model_type'=> 'asset', 
                'model_id'=>$asset->id, 
                'old_tag' => $asset->asset_tag,
                'new_tag' => $data['new_tag'],
                'location_to'=> $location_to->id, 
                'location_from' => $location_from->id, 
                'notes' => $data['notes'],
                'user_id' => auth()->user()->id, 
                'date' => \Carbon\Carbon::parse(str_replace('/', '-', $data["date"]))->format("Y-m-d"),
                'status' => 0,
            ]);

            /* If the User is a Super Admin, we can skip the approval */
            /* Moves the Asset to the Archive [SC] */
            
            if(auth()->user()->role_id == 1){
                /* If the Asset has a new Asset Tag */
                $tag = $data['new_tag'] ?? $asset->asset_tag;
                /* Move the Asset to the New Location and if required assign its new Asset Tag */
                $asset->update(['asset_tag' => $tag, 'location_id'=> $location_to->id]);
                /* Calculating its value at the time of the Transfer */
                if($asset->model()->exists()){
                    $years = $asset->model->depreciation->years;
                }else{
                    $years = 0;
                }
                $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($years);
                if($eol->isPast()){
                    $dep = 0;
                }else{
                    $age = \Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                    $percent = 100 / $years;
                    $percentage = floor($age)*$percent;
                    $dep = $asset->purchased_cost * ((100 - $percentage) / 100);
                }

                /* Create the Transfer */
                $transfer = Transfer::create([
                    'type'=>'transfer',
                    'model_type'=> 'asset', 
                    'model_id'=>$asset->id,
                    'location_to'=> $location_to->id, 
                    'location_from' => $location_from->id, 
                    'value' => number_format($dep, 2),
                    'notes' => $data['notes'],
                    'old_tag' => $asset->asset_tag,
                    'new_tag' => $data['new_tag'],
                    'date' => \Carbon\Carbon::parse(str_replace('/', '-', $data["date"]))->format("Y-m-d"),
                    'user_id' => auth()->user()->id,
                    'super_id' => auth()->user()->id,
                ]);
                /* Create a comment for the asset to log that it has been transfered */
                $user = auth()->user();
                $requests->update(['status' => 1, 'super_id'  => auth()->user()->id]);
                $comment = "The Asset - {$asset->name} [{$asset->asset_tag}] has been transfered to {$location_to->name} from {$location_from->name} by
                            {$user->name} on the {$requests->date}. {$data['notes']}";
                $asset->comment()->create(['title' => 'Asset Transfered to '.$location_to->name, 'comment' => $comment, 'user_id' => auth()->user()->id]);
            }else{
                /* Send an Email to Super Admin requesting the Disposal */
                $admins = User::superAdmin()->get();
                foreach($admins as $admin){
                    Mail::to($admin->email)->send(new \App\Mail\TransferRequest($admin, auth()->user(), 'asset', $asset->id, $location_from->name, $location_to->name, $data['date'], $data['notes']));
                }
            }
        } //end if the Asset wasnt found
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
