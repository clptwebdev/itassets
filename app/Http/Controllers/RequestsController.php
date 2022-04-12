<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asset;
use App\Models\Accessory;
use App\Models\Property;
use App\Models\Archive;
use App\Models\Requests;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Location;

use Illuminate\Support\Facades\Mail;

class RequestsController extends Controller {

    public function index()
    {
        $authUser = auth()->user()->id;
        $mangersUsers = User::whereManagerId($authUser)->pluck('id')->toArray();
        $requests = Requests::managerFilter($mangersUsers)->orderBy('created_at', 'desc')->paginate(25);
        //Returns the View for the list of requests
        $locations = Location::all();

//        $requests = Requests::orderBy('created_at', 'desc')->paginate(25);

        return view('requests.view', compact('requests', 'locations'));
    }

    public function access()
    {

        $requests = Requests::create([
            'type' => 'access',
            'notes' => 'Requesting Access to the Apollo Asset Management System at CLPT',
            'user_id' => auth()->user()->id,
            'date' => \Carbon\Carbon::now(),
            'status' => 0,
        ]);

        //Notify by email (change for new system elliot)
        $admins = User::globalAdmins()->get();
        if(auth()->user()->manager_id != null || auth()->user()->manager_id != 0)
        {
            Mail::to(auth()->user()->manager->email)->send(new \App\Mail\AccessRequest(auth()->user()->manager, auth()->user()));
        } else
        {
            foreach($admins as $admin)
            {
                Mail::to($admin->email)->send(new \App\Mail\AccessRequest($admin, auth()->user()));
            }
        }

        return back()->with('success_message', 'Your request to access the Asset Management System has been sent. Please allow 24hours for a response.');
    }

    public function transfer(Request $request)
    {

        $requests = Requests::create([
            'type' => 'transfer',
            'model_type' => $request->model_type,
            'model_id' => $request->model_id,
            'location_to' => $request->location_to,
            'location_from' => $request->location_from,
            'notes' => $request->notes,
            'user_id' => auth()->user()->id,
            'date' => $request->transfer_date,
            'status' => 0,
        ]);
        $m = "\\App\\Models\\" . ucfirst($requests->model_type);
        $model = $m::find($requests->model_id);
        if(auth()->user()->can('bypass_transfer', $model))
        {
            $model->update(['location_id' => $requests->location_to]);
            if($request->asset_tag)
            {
                $model->update(['asset_tag' => $request->asset_tag]);
            }
            if($requests->model_type == 'asset' && $model->model()->exists())
            {
                if($model->model->depreciation()->exists())
                {
                    $years = $model->model->depreciation->years;
                } else
                {
                    $years = 0;
                }
            } else if($model->depreciation_id != 0)
            {
                if($model->depreciation()->exists())
                {
                    $years = $model->depreciation->years;
                } else
                {
                    $years = 0;
                }
            } else
            {
                $years = 0;
            }
            $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
            if($eol->isPast())
            {
                $dep = 0;
            } else
            {
                $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                $percent = 100 / $years;
                $percentage = floor($age) * $percent;
                $dep = $model->purchased_cost * ((100 - $percentage) / 100);
            }
            $transfer = Transfer::create([
                'type' => 'transfer',
                'model_type' => $requests->model_type,
                'model_id' => $requests->model_id,
                'location_to' => $requests->location_to,
                'location_from' => $requests->location_from,
                'value' => number_format($dep, 2),
                'notes' => $requests->notes,
                'created_at' => $requests->date,
                'date' => $requests->date,
                'user_id' => $requests->user_id,
                'super_id' => auth()->user()->id,
            ]);
            $requests->update(['status' => 1, 'super_id' => auth()->user()->id]);

            return back()->with('success_message', 'The Request has been approved');
        } else
        {
            //Notify by email
            $admins = User::globalAdmins();
            if(auth()->user()->manager_id != null || auth()->user()->manager_id != 0)
            {
                Mail::to(auth()->user()->manager->email)->send(new \App\Mail\TransferRequest(auth()->user()->manager, auth()->user(), $requests->model_type, $requests->model_id, $requests->location_from, $requests->location_to, $requests->date, $requests->notes));
            } else
            {
                foreach($admins as $admin)
                {
                    Mail::to($admin->email)->send(new \App\Mail\TransferRequest($admin, auth()->user(), $requests->model_type, $requests->model_id, $requests->location_from, $requests->location_to, $requests->date, $requests->notes));

                }
            }

            return back()->with('success_message', 'The request to transfer the asset has been sent.');
        }
    }

    public function disposal(Request $request)
    {
        $request->validate([
            'disposed_date' => 'date',
        ]);

        $requests = Requests::create([
            'type' => 'disposal',
            'model_type' => $request->model_type,
            'model_id' => $request->model_id,
            'notes' => $request->notes,
            'date' => $request->disposed_date,
            'user_id' => auth()->user()->id,

            'status' => 0,
        ]);
        $m = "\\App\\Models\\" . ucfirst($requests->model_type);
        $model = $m::find($requests->model_id);
        if(auth()->user()->can('bypass_transfer', $model))
        {
            if($request->model_type == 'asset' && $model->model()->exists())
            {
                if($model->model->depreciation()->exists())
                {
                    $years = $model->model->depreciation->years;
                } else
                {
                    $years = 0;
                }
            } else if($model->depreciation_id != 0)
            {
                if($model->depreciation()->exists())
                {
                    $years = $model->depreciation->years;
                } else
                {
                    $years = 0;
                }
            } else
            {
                $years = 0;
            }
            $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
            if($eol->isPast())
            {
                $dep = 0;
            } else
            {
                $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                $percent = 100 / $years;
                $percentage = floor($age) * $percent;
                $dep = $model->purchased_cost * ((100 - $percentage) / 100);
            }
            foreach($model->comment as $comment)
            {
                $array = [];
                $array['title'] = $comment->title;
                $array['comment'] = $comment->comment;
                $array['user_id'] = $comment->user_id;
                $array['created_at'] = $comment->created_at;
                $array['updated_at'] = $comment->updated_at;

                $comments[] = $array;
            }

            $array = [
                'title' => 'The Asset has been Archived!',
                'comment' => "The Asset has been disposed of by " . auth()->user()->name . " for the following reasons: {$request->notes}",
                'user_id' => auth()->user()->id,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d h:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d h:i:s'),
            ];

            $comments[] = $array;

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
                'date' => $requests->date,
                'notes' => $requests->notes,
                'comments' => json_encode($comments),
            ]);
            $model->forceDelete();
            $requests->update(['status' => 1, 'super_id' => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);

            return back()->with('success_message', $message ?? 'The Disposal has been successful. It has been moved to the Archive');
        } else
        {
            //Notify by email
            $admins = User::globalAdmins();
            if(auth()->user()->manager_id != null || auth()->user()->manager_id != 0)
            {
                Mail::to(auth()->user()->manager->email)->send(new \App\Mail\DisposeRequest(auth()->user(), auth()->user()->manager, $requests->model_type, $requests->model_id, \Carbon\Carbon::parse($requests->date)->format('d-m-Y'), $requests->notes));

            } else
            {
                foreach($admins as $admin)
                {
                    Mail::to($admin->email)->send(new \App\Mail\DisposeRequest(auth()->user(), $admin, $requests->model_type, $requests->model_id, \Carbon\Carbon::parse($requests->date)->format('d-m-Y'), $requests->notes));

                }
            }

            return back()->with('success_message', 'The request to dispose the asset has been sent. Now awaiting confirmation');
        }


    }

    public function handleAccess(Request $request)
    {
        /* Get the Request */
        $req = Requests::find($request->request_id);
        //Get the User
        $user = User::find($req->user_id);
        $user->update(['location_id' => $request->location_id, 'role_id' => $request->role_id]);
        $array = explode(',', $request->permission_ids);
        $user->locations()->attach($array);
        $req->update(['status' => 1, 'super_id' => auth()->user()->id]);
        $admin = auth()->user();
        $title = "Access Approved";
        $message = "Your request to access the Apollo Asset Management System was approved by {$admin->name}";
        Mail::to($user->email)->send(new \App\Mail\ApproveRequest($user, 'Approved', 'access', $title, $message));

        return back()->with('success_message', "The Access Request has been approved and an email has been sent to {$user->name} about the decision");
    }

    public function handle(Requests $requests, $status)
    {
        $user = User::find($requests->user_id);
        if($status == 1)
        {
            switch($requests->type)
            {
                case 'transfer':
                    $m = "\\App\\Models\\" . ucfirst($requests->model_type);
                    $model = $m::find($requests->model_id);

                    if($requests->model_type == 'asset' && $model->model()->exists())
                    {
                        if($model->model->depreciation->exists())
                        {
                            $years = $model->model->depreciation->years;
                        } else
                        {
                            $years = 0;
                        }
                    } else if($model->depreciation_id != 0)
                    {
                        if($model->depreciation()->exists())
                        {
                            $years = $model->depreciation->years;
                        } else
                        {
                            $years = 0;
                        }
                    } else
                    {
                        $years = 0;
                    }
                    $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
                    if($eol->isPast())
                    {
                        $dep = 0;
                    } else
                    {
                        $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                        $percent = 100 / $years;
                        $percentage = floor($age) * $percent;
                        $dep = $model->purchased_cost * ((100 - $percentage) / 100);
                    }
                    $transfer = Transfer::create([
                        'type' => 'transfer',
                        'model_type' => $requests->model_type,
                        'model_id' => $requests->model_id,
                        'location_to' => $requests->location_to,
                        'location_from' => $requests->location_from,
                        'value' => number_format($dep, 2),
                        'notes' => $requests->notes,
                        'created_at' => $requests->date,
                        'date' => $requests->date,
                        'user_id' => $requests->user_id,
                        'super_id' => auth()->user()->id,
                    ]);
                    $requests->update(['status' => 1, 'super_id' => auth()->user()->id]);
                    $model->update(['location_id' => $requests->location_to]);
                    $comment = "The request by {$user->name} to transfer the {$requests->model_type} has been approved by " . auth()->user()->name;
                    $model->comment()->create(['title' => 'Transfer Request Approved', 'comment' => $comment, 'user_id' => auth()->user()->id]);
                    break;
                case 'disposal':
                    $m = "\\App\\Models\\" . ucfirst($requests->model_type);
                    $model = $m::find($requests->model_id);
                    if($requests->model_type == 'asset' && $model->model()->exists())
                    {
                        $years = $model->model->depreciation->years;
                    } else if($model->depreciation_id != 0)
                    {
                        $years = $model->depreciation->years;
                    } else
                    {
                        $years = 0;
                    }
                    $eol = \Carbon\Carbon::parse($model->purchased_date)->addYears($years);
                    if($eol->isPast())
                    {
                        $dep = 0;
                    } else
                    {
                        $age = \Carbon\Carbon::now()->floatDiffInYears($model->purchased_date);
                        $percent = 100 / $years;
                        $percentage = floor($age) * $percent;
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
                    $requests->update(['status' => 1, 'super_id' => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
                    break;
                default:

            }
            $admin = auth()->user();
            $title = ucfirst($requests->type) . " Request Approved";
            $message = "Your request to {$requests->type} the {$requests->model_type} was approved by {$admin->name}";
            Mail::to($user->email)->send(new \App\Mail\ApproveRequest($user, 'Approved', $requests->type, $title, $message));

            return back()->with('success_message', "The {$requests->type} Request has been approved and an email has been sent to {$user->name} about the decision");
        } else if($status == 2)
        {

            $requests->update(['status' => 2, 'super_id' => auth()->user()->id, 'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')]);
            $m = "\\App\\Models\\" . ucfirst($requests->model_type);
            $model = $m::find($requests->model_id);
            $admin = auth()->user();
            $title = ucfirst($requests->type) . " Request Denied";
            $message = "Your request to {$requests->type} the {$requests->model_type} was denied by {$admin->name}";
            Mail::to($user->email)->send(new \App\Mail\ApproveRequest($user, 'Denied', $requests->type, $title, $message));
            $comment = "The request by {$user->name} to {$requests->type} the {$requests->model_type} has been denied by " . auth()->user()->name;
            $model->comment()->create(['title' => 'Transfer Request Denied', 'comment' => $comment, 'user_id' => auth()->user()->id]);

            return back()->with('danger_message', "The Request has been denied and an email has been sent to {$user->name} about the decision");
        }
    }

}
