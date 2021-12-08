<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Archive;
use App\Models\Requests;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Location;

use Illuminate\Support\Facades\Mail;

class RequestsController extends Controller
{
    public function index(){
        $requests = Requests::orderBy('created_at', 'desc')->paginate(5);        
        return view('requests.view', compact('requests'));
    }

    public function transfer(Request $request){
        $requests = Requests::create([
            'type'=>'transfer', 
            'model_type'=> $request->model_type, 
            'model_id'=>$request->model_id, 
            'location_to'=> $request->location_to, 
            'location_from' => $request->location_from, 
            'notes' => $request->notes,
            'user_id' => auth()->user()->id, 
            'date' => $request->transfer_date,
            'status' => 0,
        ]);

        if(auth()->user()->role_id == 1){
            $m = "\\App\\Models\\".ucfirst($requests->model_type);
            $model = $m::find($requests->model_id);
            $model->update(['location_id'=> $requests->location_to]);
            if($requests->model_type == 'asset' && $model->model()->exists()){
                $years = $model->model->depreciation->years;
            }elseif($model->depreciation_id != 0){
                $years = $model->depreciation->years;
            }else{
                $years = 0;
            }
            $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
            if($eol->isPast()){
                $dep = 0;
            }else{
                $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                $percent = 100 / $years;
                $percentage = floor($age)*$percent;
                $dep = $model->purchased_cost * ((100 - $percentage) / 100);
            }
            $transfer = Transfer::create([
                'type'=>'transfer',
                'model_type'=> $requests->model_type, 
                'model_id'=>$requests->model_id,
                'location_to'=> $requests->location_to, 
                'location_from' => $requests->location_from, 
                'value' => number_format($dep, 2),
                'notes' => $requests->notes,
                'created_at' => $requests->date,
                'date' => $requests->date,
                'user_id' => $requests->user_id,
                'super_id' => auth()->user()->id,
            ]);
            $requests->update(['status' => 1, 'super_id'  => auth()->user()->id]);
            return back()->with('success_message','The Request has been approved');
        }else{
            //Notify by email
            $admins = User::where('role_id', '=', 1)->get();
            foreach($admins as $admin){
                Mail::to('stuart.corns@clpt.co.uk')->send(new \App\Mail\AlertRequest(auth()->user()->id, $requests->model_type, $requests->model_id, $requests->location_from, $requests->location_to, $requests->date, $requests->comment));
            }
            return back()->with('success_message', 'The request to transfer the asset has been sent.');
        }  
    }

    public function disposal(Request $request){
        
        $requests = Requests::create([
            'type'=>'disposal', 
            'model_type'=> $request->model_type, 
            'model_id'=>$request->model_id,
            'notes' => $request->notes,
            'date' => $request->disposed_date,
            'user_id' => auth()->user()->id, 
            'status' => 0,
        ]);
        
        if(auth()->user()->role_id == 1){
            $m = "\\App\\Models\\".ucfirst($requests->model_type);
            $model = $m::find($request->model_id);

            if($request->model_type == 'asset' && $model->model()->exists()){
                $years = $model->model->depreciation->years;
            }elseif($model->depreciation_id != 0){
                $years = $model->depreciation->years;
            }else{
                $years = 0;
            }
            $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
            if($eol->isPast()){
                $dep = 0;
            }else{
                $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                $percent = 100 / $years;
                $percentage = floor($age)*$percent;
                $dep = $model->purchased_cost * ((100 - $percentage) / 100);
            }

            $archive = Archive::create([
                'model_type' => $request->model_type ?? 'unknown',
                'name' => $model->name ?? 'No Name',
                'asset_tag' => $model->asset_tag ?? 'No Asset Tag',
                'serial_no' => $model->serial_no ?? 'N/A',
                'asset_model' => $model->model->name ?? 'No Model',
                'order_no' => $model->order_no ?? 'N/A',
                'supplier_id' => $model->supplier_id ?? 0,
                'purchased_date' => $model->purchased_date,
                'purchased_cost' => $model->purchased_cost,
                'archived_cost' => number_format($dep, 2),
                'warranty' => $model->warranty,
                'location_id' => $model->location_id ?? 0,
                'room' => $model->room ?? 'N/A',
                'logs' => 'N/A',
                'comments' => 'N/A',
                'created_user' => $model->user_id ?? 0,
                'created_on' => $model->created_at,
                'user_id' => auth()->user()->id,
                'super_id' => auth()->user()->id,
                'date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'notes' => $request->notes,
            ]);
            $model->forceDelete();
            $requests->update(['status' => 1, 'super_id'  => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
            $message = "The Disposal has been successful. It has been moved to the Archive";
        }

        session()->flash('success_message', $message ?? 'The request to transfer the asset has been sent.');
        return back();
    }

    public function handle(Requests $requests, $status){
        if($status == 1){
            
            switch($requests->type){
                case 'transfer':
                    $m = "\\App\\Models\\".ucfirst($requests->model_type);
                    $model = $m::find($requests->model_id);
                    $model->update(['location_id'=> $requests->location_to]);
                    if($requests->model_type == 'asset' && $model->model()->exists()){
                        $years = $model->model->depreciation->years;
                    }elseif($model->depreciation_id != 0){
                        $years = $model->depreciation->years;
                    }else{
                        $years = 0;
                    }
                    $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
                    if($eol->isPast()){
                        $dep = 0;
                    }else{
                        $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                        $percent = 100 / $years;
                        $percentage = floor($age)*$percent;
                        $dep = $model->purchased_cost * ((100 - $percentage) / 100);
                    }
                    $transfer = Transfer::create([
                        'type'=>'transfer',
                        'model_type'=> $requests->model_type, 
                        'model_id'=>$requests->model_id,
                        'location_to'=> $requests->location_to, 
                        'location_from' => $requests->location_from, 
                        'value' => number_format($dep, 2),
                        'notes' => $requests->notes,
                        'created_at' => $requests->date,
                        'date' => $requests->date,
                        'user_id' => $requests->user_id,
                        'super_id' => auth()->user()->id,
                    ]);
                    $requests->update(['status' => 1, 'super_id'  => auth()->user()->id]);
                    return back()->with('success_message','The Request has been approved');
                    
                    break;
                case 'disposal':
                    $m = "\\App\\Models\\".ucfirst($requests->model_type);
                    $model = $m::find($requests->model_id);

                    if($requests->model_type == 'asset' && $model->model()->exists()){
                        $years = $model->model->depreciation->years;
                    }elseif($model->depreciation_id != 0){
                        $years = $model->depreciation->years;
                    }else{
                        $years = 0;
                    }
                    $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
                    if($eol->isPast()){
                        $dep = 0;
                    }else{
                        $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                        $percent = 100 / $years;
                        $percentage = floor($age)*$percent;
                        $dep = $model->purchased_cost * ((100 - $percentage) / 100);
                    }

                    $archive = Archive::create([
                        'model_type' => $requests->model_type ?? 'unknown',
                        'name' => $model->name ?? 'No Name',
                        'asset_tag' => $model->asset_tag ?? 'No Asset Tag',
                        'serial_no' => $model->serial_no ?? 'N/A',
                        'asset_model' => $model->model->name ?? 'No Model',
                        'order_no' => $model->order_no ?? 'N/A',
                        'supplier_id' => $model->supplier_id ?? 0,
                        'purchased_date' => $model->purchased_date,
                        'purchased_cost' => $model->purchased_cost,
                        'archived_cost' => number_format($dep, 2),
                        'warranty' => $model->warranty,
                        'location_id' => $model->location_id ?? 0,
                        'room' => $model->room ?? 'N/A',
                        'logs' => 'N/A',
                        'comments' => 'N/A',
                        'created_user' => $model->user_id ?? 0,
                        'created_on' => $model->created_at,
                        'user_id' => $requests->user_id,
                        'super_id' => auth()->user()->id,
                        'date' => $requests->date,
                        'notes' => $requests->notes,
                    ]);
                    $model->forceDelete();
                    $requests->update(['status' => 1, 'super_id'  => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
                    return back()->with('success_message','The Request has been approved');
                    break;
                default:

            }
        }elseif($status == 2){
            $requests->update(['status' => 2, 'super_id' => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
            return back()->with('danger_message','The Request has been denied');
        }
    }
}
