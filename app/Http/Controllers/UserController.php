<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
use App\Jobs\ColumnLogger;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use App\Jobs\UsersPdf;
use App\Jobs\UserPdf;
use App\Models\Report;
use Schema;

class UserController extends Controller {

    public function index()
    {
        if(auth()->user()->cant('viewAll', User::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Users.');
        }
        $users = User::whereHas('locations', function($query) {
            $locs = [];
            foreach(auth()->user()->locations as $loc)
            {
                $locs[] = $loc->id;
            }
            $query->whereIn('locations.id', $locs);
        })->get();

        return view('users.view', compact('users'));
    }

    public function create()
    {
        if(auth()->user()->cant('create', User::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Create Users.');
        }
        $roles = Role::all();

        $locations = auth()->user()->locations;

        return view('users.create', compact('locations', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email:rfc,dns,spoof,filter',
            'role_id' => 'required',
        ]);

        $user = new User;
        $unhash = $user->random_password(12);
        $password = Hash::make($unhash);
        $user->fill(['name' => $request->name, 'telephone' => $request->telephone, 'email' => $request->email, 'location_id' => $request->location_id, 'role_id' => $request->role_id, 'password' => $password])->save();
        Mail::to($request->email)->send(new \App\Mail\NewUserPassword($user, $unhash));

        $array = explode(',', $request->permission_id);

        $user->locations()->attach($array);

        Mail::to('apollo@clpt.co.uk')->send(new \App\Mail\CreatedUser(auth()->user(), $user));
        session()->flash('success_message', $request->name . ' has been created successfully');

        return to_route('users.index');
    }

    public function show(User $user)
    {
        if(auth()->user()->cant('view', $user))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Show User.');

        }

        $location = Location::find($user->location_id);

        return view('users.show', compact('user', 'location'));
    }

    public function edit(User $user)
    {
        if(auth()->user()->cant('update', $user))
        {
            return ErrorController::forbidden(route('users.index'), 'Unauthorised to Edit User.');
        }
        if(auth()->user()->role->significance >= $user->role->significance)
        {
            $roles = Role::significance($user);
            $locations = auth()->user()->locations;

            return view('users.edit', compact('user', 'locations', 'roles'));
        } else
        {
            return ErrorController::forbidden(route('users.index'), 'Unauthorised to Edit This User (Incorrect Significance)');
        }


    }

    public function update(Request $request, User $user)
    {

        if(auth()->user()->cant('update', $user))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Edit User.');

        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', \Illuminate\Validation\Rule::unique('users')->ignore($user->id), 'email:rfc,dns,spoof,filter'],
            'role_id' => 'required',
        ]);

        $user->fill($request->only('name', 'email', 'location_id', 'role_id', 'telephone'))->save();
        $array = explode(',', $request->permission_ids);
        $user->locations()->sync($array);

        session()->flash('success_message', $request->name . ' has been updated successfully');

        return to_route('users.index');
    }

    public function managerUpdate(Request $request)
    {
        $request->validate([
            'manager_id' => 'required',
            'selectedUser' => 'required',
        ]);
        $user = User::whereId($request->selectedUser)->first();
        $manager = User::whereId($request->manager_id)->first();
        if($request->manager_id == $user->manager_id)
        {
            session()->flash('danger_message', $user->name . ' Is Already assigned to this Manager.');

            return to_route('users.index');
        } else if($request->manager_id == $user->id)
        {
            session()->flash('danger_message', 'You Cannot be your own Manager. Please select another user to be your Manager.');

            return to_route('users.index');
        } else
        {
            $user->update([
                'manager_id' => $request->manager_id,
            ]);

            session()->flash('success_message', $user->name . ' Has Been assigned to the Manager ' . $manager->name . '.');

            return to_route('user.details');
        }
    }

    public function destroy(User $user)
    {
        if(auth()->user()->cant('delete', $user))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Delete User.');

        }

        $name = $user->name;
        $user->delete();
        Mail::to('apollo@clpt.co.uk')->send(new \App\Mail\DeletedUser(auth()->user(), $name));
        session()->flash('danger_message', $name . ' was deleted from the system');

        return to_route('users.index');
    }

    public function export(User $user)
    {
        if(auth()->user()->cant('viewAll', User::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Export Users.');

        }

        return \Maatwebsite\Excel\Facades\Excel::download(new UserExport, 'users.xlsx');

    }

    public function permissions(Request $request)
    {

        if(auth()->user()->cant('viewAll', User::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Permissions.');

        }
        $ids = explode(',', $request->ids);

        return view('users.permissions', compact('ids'));
    }

    public function userPermissions()
    {
        if(auth()->user()->cant('viewAll', User::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View Permissions.');

        }

        return view('users.roles');
    }

    public function changePermission($id, $role)
    {

        $user = User::findOrFail($id);

        $user->role_id = $role;

        $user->save();
    }

    public function getLocations($id)
    {
        $user = User::findOrFail($id);

        return view('users.locations', compact('user'));
    }

    public function userDetails()
    {
        return view('user.details');
    }

    public function updateDetails(Request $request)
    {
        if(auth()->user()->cant('view', auth()->user()))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to View User.');

        }
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', \Illuminate\Validation\Rule::unique('users')->ignore(auth()->user()->id), 'email:rfc,dns,spoof,filter'],
        ]);

        auth()->user()->fill($request->only('name', 'email', 'photo_id'))->save();
        session()->flash('success_message', $request->name . ', you have successfully updated your details.');

        return to_route('dashboard');
    }

    public function userPassword()
    {
        return view('user.password');
    }

    public function forgotPassword()
    {
        return view('user.internal-forgot-password');
    }

    public function storePass(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'oldPassword' => 'required',
            'newPassword' => 'required',
            'confirmNewPassword' => 'required',
        ]);
        $user = User::where('name', auth()->user()->name)->first();
        $hashCheck = \Illuminate\Support\Facades\Hash::check($request->oldPassword, auth()->user()->password);
        $newCheck = $request->newPassword === $request->confirmNewPassword;
        if($hashCheck && $newCheck === true)
        {
            $newPasswordHashed = Hash::make($request->newPassword);
            $user->password = $newPasswordHashed;
            $user->save();
            session()->flash('success_message', auth()->user()->name . ', you have successfully updated your Password.');

            return to_route("user.details");

        } else
        {
            return to_route('user.details')
                ->with('danger_message', "Your Password Didn't match your current password please try again!");
        }
    }

    public function downloadPDF(Request $request)
    {
        if(auth()->user()->cant('viewAll', User::class))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Download Users.');
        }

        $users = User::all();
        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'users-' . $date;
        UsersPdf::dispatch($users, $user, $path)->afterResponse();

        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $user->id]);

        return to_route('users.index')
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(User $user)
    {
        if(auth()->user()->cant('view', $user))
        {
            return ErrorController::forbidden(route('dashboard'), 'Unauthorised to Download Users.');

        }

        $admin = auth()->user();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = "{$user->name}-{$date}";
        UserPdf::dispatch($user, $admin, $path)->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report' => $url, 'user_id' => $admin->id]);

        return to_route('users.show', $user->id)
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function invokeExpiredUsers()
    {
        //This function checks if the user has signed in the last 3 Months if they haven't set there role to temp
        foreach(User::all() as $user)
        {
            if($user->expiredUser())
            {
                $user->role_id = Role::whereName('temporary')->first()->id;
                $user->save();
            }

        }

        return to_route('users.index')->with('success_message', 'All Users have been Checked for Inactivity');

    }

}
