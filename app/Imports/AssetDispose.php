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
use App\Models\Archive;

class AssetDispose implements ToModel, WithValidation, WithHeadingRow, WithBatchInserts, WithUpserts, SkipsOnFailure, SkipsOnError {

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
                if($data['id'] != null && $asset = Asset::find($data['id']))
                {
                    /* If the user has added an ID (Unlikely) */
                    /* But trys to find it in this instance */
                    $validator->errors()->add($key . '.id', 'An Asset could not be found using the entered details');
                } else
                {
                    /* If ID is not entered or an Asset is not found using the ID */
                    /* Location will then be required to try and find the Asset Tag and/or Serial No */
                    if($data['location_id'] != null && $location = Location::whereName($data['location_id'])->first())
                    {
                        if($data['serial_no'] == null && $data['asset_tag'] == null)
                        {
                            $validator->errors()->add($key . '.serial_no', 'Please enter the Serial No and/or the Asset Tag - there is not enough information to locate the Asset');
                            $validator->errors()->add($key . '.asset_tag', 'Please enter the Serial No and/or the Asset Tag - there is not enough information to locate the Asset');

                        } else
                        {
                            $asset = Asset::where('location_id', '=', $location->id);
                            if($data['serial_no'] != '')
                            {
                                $asset->where('serial_no', '=', $data['serial_no']);
                            }
                            if($data['asset_tag'] != '')
                            {
                                $asset->where('asset_tag', '=', $data['asset_tag']);
                            }
                            /* If the Validation has been passed successfully */
                            /* Create a new request to dispose this Asset [SC]*/
                            if($asset->first() == null)
                            {
                                $validator->errors()->add($key . '.asset_tag', 'An Asset could not be found using the entered details');
                            }
                        }
                    } else
                    {
                        $validator->errors()->add($key . '.location_id', 'Please enter a Location');
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
            'reason' => [
                'required',
            ],
            'location_id' => [
                'required',
            ],
        ];


    }

    public function model(array $data)
    {

        $location = Location::whereName($data['location_id'])->first();

        $find = Asset::where('location_id', '=', $location->id);
        if($data['serial_no'] != '')
        {
            $find->where('serial_no', '=', $data['serial_no']);
        }
        if($data['asset_tag'] != '')
        {
            $find->where('asset_tag', '=', $data['asset_tag']);
        }
        $asset = $find->first();
        /* If the Validation has been passed successfully */
        /* Create a new request to dispose this Asset [SC]*/
        if($asset)
        {
            $requests = Requests::create([
                'type' => 'disposal',
                'model_type' => 'asset',
                'model_id' => $asset->id,
                'notes' => $data['reason'],
                'date' => \Carbon\Carbon::parse(str_replace('/', '-', $data["date"]))->format("Y-m-d"),
                'user_id' => auth()->user()->id,
                'status' => 0,
            ]);

            /* If the User is a Super Admin, we can skip the approval */
            /* Moves the Asset to the Archive [SC] */

            if(auth()->user()->can('dispose', Asset::class))
            {
                /* Get the Depreciaiton Years from the Asset Model or 0 */
                /* This is to calculate the Value of the Asset at the time of Disposal [SC] */
                $years = $asset->model->depreciation->years ?? 0;
                $eol = \Carbon\Carbon::parse($asset->purchased_date)->addYears($years);
                if($eol->isPast())
                {
                    $dep = 0;
                } else
                {
                    $age = \Carbon\Carbon::now()->floatDiffInYears($asset->purchased_date);
                    $percent = 100 / $years;
                    $percentage = floor($age) * $percent;
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
                    'date' => \Carbon\Carbon::parse(str_replace('/', '-', $data["date"]))->format("Y-m-d"),
                    'notes' => $data['reason'],
                ]);
                $asset->forceDelete();
                $requests->update(['status' => 1, 'super_id' => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
            } else
            {
                //Notify by email
                $admins = User::superAdmin()->get();
                foreach($admins as $admin)
                {
                    Mail::to($admin->email)->send(new \App\Mail\TransferRequest($admin, auth()->user(), $requests->model_type, $requests->model_id, $requests->location_from, $requests->location_to, $requests->date, $requests->comment));
                }

            }
        } //end if the Asset wasnt found

        $this->asset = null;
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
