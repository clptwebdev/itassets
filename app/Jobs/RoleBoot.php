<?php

namespace App\Jobs;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RoleBoot implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //created default roles
        $global_admin = Role::updateOrCreate([
            'name' => 'global_admin',
            'significance' => '6',
        ]);
        $it_manager = Role::updateOrCreate([
            'name' => 'it_manager',
            'significance' => '5',
        ]);
        $business_manager = Role::updateOrCreate([
            'name' => 'Business/finance_manager',
            'significance' => '4',
        ]);
        $technician = Role::updateOrCreate([
            'name' => 'technnician',
            'significance' => '3',
        ]);
        $user_manager = Role::updateOrCreate([
            'name' => 'user_manager',
            'significance' => '2',
        ]);
        $user = Role::updateOrCreate([
            'name' => 'user',
            'significance' => '1',
        ]);
        // global team start
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'AUC'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        // global team end
    }

}
