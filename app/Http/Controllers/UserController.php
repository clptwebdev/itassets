<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Http\Controllers\Controller;
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

class UserController extends Controller {

    public function __construct()
    {

    }

    public function index()
    {
        if(auth()->user()->cant('viewAll', User::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Users', 'view']));
        }

        if(auth()->user()->role_id == 1)
        {
            $users = User::all();
        } else
        {
            $users = User::whereHas('locations', function($query) {
                $locs = [];
                foreach(auth()->user()->locations as $loc)
                {
                    $locs[] = $loc->id;
                }
                $query->whereIn('locations.id', $locs);
            })->get();
        }

        return view('users.view', compact('users'));
    }

    public function create()
    {
        if(auth()->user()->role_id == 1)
        {
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;
        }

        return view('users.create', compact('locations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email:rfc,dns,spoof,filter',
        ]);

        $user = new User;
        $unhash = $user->random_password(12);
        $password = Hash::make($unhash);
        $user->fill(['name' => $request->name, 'telephone' => $request->telephone, 'email' => $request->email, 'location_id' => $request->location_id, 'role_id' => $request->role_id, 'password' => $password])->save();
        Mail::to('stuartcorns@outlook.com')->send(new \App\Mail\NewUserPassword($user, $unhash));

        $array = explode(',', $request->permission_ids);
        $user->locations()->attach($array);
        session()->flash('success_message', $request->name . ' has been created successfully');

        return redirect(route('users.index'));
    }

    public function show(User $user)
    {
        if(auth()->user()->cant('view', $user))
        {
            return redirect(route('errors.forbidden', ['user', $user->id, 'view']));
        }

        $location = Location::find($user->location_id);

        return view('users.show', compact('user', 'location'));
    }

    public function edit(User $user)
    {
        if(auth()->user()->cant('update', $user))
        {
            return redirect(route('errors.forbidden', ['user', $user->id, 'edit']));
        }

        if(auth()->user()->role_id == 1)
        {
            $locations = Location::all();
        } else
        {
            $locations = auth()->user()->locations;
        }

        return view('users.edit', compact('user', 'locations'));

    }

    public function update(Request $request, User $user)
    {

        if(auth()->user()->cant('update', $user))
        {
            return redirect(route('errors.forbidden', ['user', $user->id, 'edit']));
        }

        $validated = $request->validate([
            'name' => 'required|max:255',
            'telephone' => 'regex:/(01)[0-9]{9}/|nullable',
            'email' => ['required', \Illuminate\Validation\Rule::unique('users')->ignore($user->id), 'email:rfc,dns,spoof,filter'],
        ]);

        $user->fill($request->only('name', 'email', 'location_id', 'role_id','telephone'))->save();
        $array = explode(',', $request->permission_ids);
        $user->locations()->sync($array);

        session()->flash('success_message', $request->name . ' has been updated successfully');

        return redirect(route('users.index'));
    }

    public function destroy(User $user)
    {
        if(auth()->user()->cant('delete', $user))
        {
            return redirect(route('errors.forbidden', ['user', $user->id, 'edit']));
        }

        $name = $user->name;
        $user->delete();
        session()->flash('danger_message', $name . ' was deleted from the system');

        return redirect(route('users.index'));
    }

    public function export(User $user)
    {
        if(auth()->user()->cant('viewAll', User::class))
        {
            return redirect(route('errors.forbidden', ['area', 'Users', 'export']));
        }

        return \Maatwebsite\Excel\Facades\Excel::download(new UserExport, 'users.csv');

    }

    public function permissions(Request $request)
    {
        if($request->ajax())
        {
            $ids = $request->ids;

            return view('users.permissions', compact('ids'));
        } else
        {
            return 'Not Ajax';
        }
    }

    public function userPermissions()
    {
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

        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => ['required', \Illuminate\Validation\Rule::unique('users')->ignore(auth()->user()->id), 'email:rfc,dns,spoof,filter'],
        ]);

        auth()->user()->fill($request->only('name', 'email', 'photo_id'))->save();
        session()->flash('success_message', $request->name . ', you have successfully updated your details.');

        return redirect('/dashboard');
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
        $user = User::where('name',auth()->user()->name)->first();
        $hashCheck = \Illuminate\Support\Facades\Hash::check($request->oldPassword, auth()->user()->password);
        $newCheck = $request->newPassword === $request->confirmNewPassword;
        if($hashCheck && $newCheck === true)
        {
            $newPasswordHashed = Hash::make($request->newPassword);
            $user->password = $newPasswordHashed;
            $user->save();
            session()->flash('success_message', auth()->user()->name . ', you have successfully updated your Password.');

                   return redirect(route("user.details"));

        }else{
            return redirect(route('user.details'))
                ->with('danger_message', "Your Password Didn't match your current password please try again!");
        }
    }

    public function downloadPDF(Request $request)
    {
        if (auth()->user()->cant('viewAll', User::class)) {
            return redirect(route('errors.forbidden', ['area', 'User', 'View PDF']));
        }

        $users = User::all();
        $user = auth()->user();

        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = 'users-'.$date;
        UsersPdf::dispatch( $users,$user,$path )->afterResponse();


        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $user->id]);
        return redirect(route('users.index'))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }

    public function downloadShowPDF(User $user)
    {
        if (auth()->user()->cant('view', $user)) {
            return redirect(route('errors.forbidden', ['asset', $user->id, 'View PDF']));
        }

        $admin = auth()->user();
        $date = \Carbon\Carbon::now()->format('d-m-y-Hi');
        $path = "{$user->name}-{$date}";
        UserPdf::dispatch( $user,$admin,$path )->afterResponse();
        $url = "storage/reports/{$path}.pdf";
        $report = Report::create(['report'=> $url, 'user_id'=> $admin->id]);

        return redirect(route('users.show', $user->id))
            ->with('success_message', "Your Report is being processed, check your reports here - <a href='/reports/' title='View Report'>Generated Reports</a> ")
            ->withInput();
    }
}
