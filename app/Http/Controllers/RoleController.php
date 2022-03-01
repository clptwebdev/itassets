<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\RoleBoot;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller {

    public function store(Request $request)
    {

        $name = str_replace(' ', '_', ucfirst($request->name));
        $request->validate([
            "name" => "required|string|Unique:roles",
            "create" => "nullable",
            "update" => "nullable",
            "view" => "nullable",
            "delete" => "nullable",
            "archive" => "nullable",
            "transfer" => "nullable",
            "request" => "nullable",
            "spec_reports" => "nullable",
            "fin_reports" => "nullable",
        ]);
        $role = Role::create([
            'name' => $name,
        ]);
        foreach($request->models as $model)
        {
            $readable = 0;
            $createable = 0;
            $updateable = 0;
            $deleteable = 0;
            $achievable = 0;
            $transferable = 0;
            $requestable = 0;
            $spec_reportable = 0;
            $fin_reportable = 0;

            if($request->read != null)
            {
                if(in_array($model, $request->read))
                {
                    $readable = 1;
                }
            }
            if($request->create != null)
            {
                if(in_array($model, $request->create))
                {
                    $createable = 1;
                }
            }
            if($request->update != null)
            {
                if(in_array($model, $request->update))
                {
                    $updateable = 1;
                }
            }
            if($request->delete != null)
            {
                if(in_array($model, $request->delete))
                {
                    $deleteable = 1;
                }
            }
            if($request->archive != null)
            {
                if(in_array($model, $request->archive))
                {
                    $achievable = 1;
                }
            }
            if($request->transfer != null)
            {
                if(in_array($model, $request->transfer))
                {
                    $transferable = 1;
                }
            }
            if($request->keys('request') != null)
            {
                if(in_array($model, $request->keys('request')))
                {
                    $requestable = 1;
                }
            }
            if($request->spec_reports != null)
            {
                if(in_array($model, $request->spec_reports))
                {
                    $spec_reportable = 1;
                }
            }
            if($request->fin_reports != null)
            {
                if(in_array($model, $request->fin_reports))
                {
                    $fin_reportable = 1;
                }
            }
            Permission::Create([
                'role_id' => $role->id,
                'model' => $model,
                "create" => $createable,
                "update" => $updateable,
                "view" => $readable,
                "delete" => $deleteable,
                "archive" => $achievable,
                "transfer" => $transferable,
                "request" => $requestable,
                "spec_reports" => $spec_reportable,
                "fin_reports" => $fin_reportable,
            ]);

        }

        return redirect(route('settings.view'))->with('success_message', "Your Role '" . $role->name . "' has been created. To assign this please head to the users page!");

    }

    public function roleSync(Request $request)
    {

        $user = User::whereId($request->user)->first();
        $role = Role::whereId($request->role)->first();
        $user->update([
            'role_id' => $role->id,
        ]);
        $user->save();

        return redirect(route('settings.view'))->with('success_message', $user->name . ' Has now been assigned the role ' . $role->name . '!');

    }

    public function destroy(Request $request)
    {

        $role = Role::whereId($request->role)->first();
        $role->delete();

        return redirect(route('settings.view'))->with('danger_message', 'This Role Has now been Deleted !');
    }

    public function default()
    {
//        RoleBoot::dispatch();

        return redirect(route('dashboard'))->with('success_message', 'Default roles Has now been created you can assign them in the settings!');

    }

}
