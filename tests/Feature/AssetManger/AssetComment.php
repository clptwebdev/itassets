<?php

namespace AssetManger;

use App\Jobs\RoleBoot;
use App\Models\Asset;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AssetComment extends TestCase {

    // must start with test_ to run test
    public function test_with_factories()
    {
        //shows error instead of request number like 404
        $this->withoutExceptionHandling();
        RoleBoot::dispatch();
        $asset = Asset::factory()->has(Comment::factory()
            ->count(5))->create();
        $this->assertCount(5, $asset->comment);

    }

}
