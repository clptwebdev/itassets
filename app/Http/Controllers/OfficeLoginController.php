<?php

namespace App\Http\Controllers;

use App\Jobs\RoleBoot;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OfficeLoginController extends Controller {

    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {

        return Socialite::driver('azure')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('azure')->user();
        $bootRoles = User::count();
        if($bootRoles == 0)
        {
            // create default roles
            RoleBoot::dispatch();
        }
        if($authUser = User::whereEmail($user->email)->first())
        {

        } else
        {
            $developer = Role::whereName('Developer')->first();
            $firstUser = User::count();
            $noPerm = Role::whereName('temporary')->first();
            $authUser = new User;
            $unhash = $authUser->random_password(12);
            $password = Hash::make($unhash);
            $authUser->fill([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'role_id' => $noPerm->id ?? 7,
            ])->save();

            if($firstUser === 1)
            {
                $authUser->update(['role_id' => $developer->id ?? 1]);
            }
            Mail::to($user->email)->send(new \App\Mail\NewUserPassword($authUser, $unhash));

            /*  Mail::send('emails.tpl', $data, function($message){
                $message->to('stuartcorns@outlook.com', 'Stuart')->subject('Email with Laravel and AWS');
            }); */
        }

        auth()->login($authUser, false);

        if(session()->has('url.intended'))
        {
            return redirect(session('url.intended'));
        }

        return to_route('dashboard');
    }

}
