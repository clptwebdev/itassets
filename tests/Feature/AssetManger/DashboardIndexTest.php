<?php

namespace AssetManger;

use App\Jobs\RoleBoot;
use App\Models\Component;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardIndexTest extends TestCase {

    use RefreshDatabase;

    public function test_index_shows_Dashboard()
    {
        RoleBoot::dispatch();
        //shows error instead of request number like 404
        $this->withoutExceptionHandling();
        $user = User::factory(
            ['role_id' => '1'],
        )->create();
        $this->login();
        Manufacturer::factory()->has(Component::factory())
            ->count(4)
            ->sequence(
                ['name' => 'happy'],
                ['name' => 'days'],
                ['name' => 'woo'],
                ['name' => 'hello'],
            )->create();

        $this->actingAs($user)->get('/manufacturers')
            ->assertSuccessful();
    }

}
