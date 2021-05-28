<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class OfficeLoginController extends Controller
{
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

        $authUser = User::firstOrCreate(['email' => $user->email], [
            'name' => $user->name,
            'email' => $user->email,
            'password' => 'Test123',
        ]);

        /* if($authUser->wasRecentlyCreated) {
            (new StoreAndEmailTemporaryPasswordAction())->execute($authUser);
        }
 */
        auth()->login($authUser, false);

        return redirect('/');
    }
}
