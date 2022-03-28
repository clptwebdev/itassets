<?php

namespace AssetManger;

use App\Http\Controllers\UserController;
use App\Jobs\RoleBoot;
use App\Mail\NewUserPassword;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Testing\Fakes\MailFake;
use Tests\TestCase;

class UserControllerMail extends TestCase {

    public function test_New_User_Creation()
    {
        $this->withoutExceptionHandling();

        Mail::fake();
        $word = 'testing123';

        RoleBoot::dispatch();
        $user = User::factory()->create([
            'email' => 'elliot.putt@clpt.co.uk',
            'role_id' => '1',
            'password' => Hash::make($word),
        ]);

        $this->actingAs($user)->get('/users')
            ->assertSuccessful();

        Mail::to($user->email)->send(new NewUserPassword($user, $word));
        Mail::assertSent(NewUserPassword::class);

        $this->assertDatabaseHas(User::class, [
            'name' => $user->name,
        ]);


    }

}
