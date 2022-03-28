<?php

namespace Tests;

use App\Jobs\RoleBoot;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {

    use RefreshDatabase;
    use CreatesApplication;

    public function login(User $user = null): User
    {
        $user ??= $user = User::factory()->create([
            'role_id' => 1,
        ]);
        $this->actingAs($user);

        return $user;
    }

}
