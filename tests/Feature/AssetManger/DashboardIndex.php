<?php

namespace Tests\Feature\AssetManger;

use App\Models\Component;
use App\Models\Manufacturer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardIndex extends TestCase {
use RefreshDatabase;
    public function test_index_shows_Dashboard()
    {
        //shows error instead of request number like 404
        $this->withoutExceptionHandling();
$user = User::factory(
    ['role_id' => '1'],
)->create();
        Manufacturer::factory()->has(Component::factory())
            ->count(4)
            ->sequence(
         ['name' => 'happy'],
         ['name' => 'days'],
         ['name' => 'woo'],
         ['name' => 'hello'],
                )->create();


        $this->actingAs($user)->get('/manufacturers')
            ->assertSuccessful()
            ->assertSee('happy')
            ->assertSeeInOrder([
                'days',
                'woo',
            ]);
    }

}
