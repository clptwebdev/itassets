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
            'id' => 1], [
            'name' => 'global_admin',
            'significance' => '5',
        ]);
        $it_manager = Role::updateOrCreate([
            'id' => 2], [
            'name' => 'it_manager',
            'significance' => '4',
        ]);
        $business_manager = Role::updateOrCreate([
            'id' => 3], [
            'name' => 'Business_manager',
            'significance' => '4',
        ]);
        $technician = Role::updateOrCreate([
            'id' => 4], [
            'name' => 'technician',
            'significance' => '3',
        ]);
        $user_manager = Role::updateOrCreate([
            'id' => 5], [
            'name' => 'user_manager',
            'significance' => '2',
        ]);
        $user = Role::updateOrCreate([
            'id' => 6], [
            'name' => 'user',
            'significance' => '1',
        ]);
        $temp = Role::updateOrCreate([
            'id' => 7], [
            'name' => 'temporary',
            'significance' => '0',
        ]);
        // global team start
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Broadband'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'License'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Requests'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Transfer'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Archive'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Report'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'FFE'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Machinery'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Vehicle'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Software'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Accessory'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Asset'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'AssetModel'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Backup'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Category'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Comment'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Component'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Consumable'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Depreciation'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Field'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Fieldset'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Location'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'LocationUser'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Log'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Manufacturer'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Miscellanea'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Photo'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Property'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Role'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Status'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Supplier'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'User'], [
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
        Permission::updateOrCreate([
            'role_id' => $global_admin->id,
            'model' => 'Order'], [
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

        // IT manager team start
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Broadband'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'License'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Requests'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Transfer'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Archive'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Report'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'AUC'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'FFE'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Machinery'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Vehicle'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Software'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Accessory'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Asset'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'AssetModel'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Backup'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Category'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Comment'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Component'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Consumable'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Depreciation'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Field'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Fieldset'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Location'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'LocationUser'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Log'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Manufacturer'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Miscellanea'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Photo'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Property'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Role'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Status'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Supplier'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'User'], [
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
        Permission::updateOrCreate([
            'role_id' => $it_manager->id,
            'model' => 'Order'], [
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

        // It Manager end

        // Business Manager start
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Broadband'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'License'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'AUC'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'FFE'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Machinery'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Vehicle'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Software'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Accessory'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Asset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'AssetModel'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Backup'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Category'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Comment'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Component'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Consumable'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Depreciation'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Field'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Fieldset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Location'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'LocationUser'], [
            "Create" => 0,
            "update" => 1,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Log'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Manufacturer'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Miscellanea'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Photo'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Property'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 1,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Role'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Status'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Supplier'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'User'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $business_manager->id,
            'model' => 'Order'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);

        // Business Manager end
        // Technician start
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Order'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Broadband'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 1,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'License'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 1,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Requests'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Transfer'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Archive'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Report'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'AUC'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'FFE'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Machinery'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Vehicle'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Software'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Accessory'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Asset'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'AssetModel'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Backup'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Category'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Comment'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Component'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Consumable'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Depreciation'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Field'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Fieldset'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Location'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'LocationUser'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Log'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Manufacturer'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Miscellanea'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 1,
            "transfer" => 1,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 1,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Photo'], [
            "Create" => 1,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Property'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Role'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Status'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'Supplier'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $technician->id,
            'model' => 'User'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);

        // Technician end
        // User Manager start
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Order'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Broadband'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'License'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Requests'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Transfer'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Archive'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Report'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'AUC'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'FFE'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Machinery'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Vehicle'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Software'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Accessory'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Asset'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'AssetModel'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 1,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Backup'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Category'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Comment'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Component'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Consumable'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Depreciation'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Field'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Fieldset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Location'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'LocationUser'], [
            "Create" => 0,
            "update" => 1,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Log'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Manufacturer'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Miscellanea'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Photo'], [
            "Create" => 1,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Property'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Role'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Status'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'Supplier'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user_manager->id,
            'model' => 'User'], [
            "Create" => 1,
            "update" => 1,
            "view" => 1,
            "delete" => 1,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);

        // User Manager end
        // User start
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Order'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Broadband'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'License'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Requests'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Transfer'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Archive'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Report'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'AUC'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'FFE'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Machinery'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Vehicle'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Software'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Accessory'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Asset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'AssetModel'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Backup'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Category'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Comment'], [
            "Create" => 1,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Component'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Consumable'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Depreciation'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Field'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Fieldset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Location'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'LocationUser'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Log'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Manufacturer'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Miscellanea'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Photo'], [
            "Create" => 1,
            "update" => 1,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Property'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Role'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Status'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'Supplier'], [
            "Create" => 0,
            "update" => 0,
            "view" => 1,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $user->id,
            'model' => 'User'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);

        // User end
        // temporary start
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Order'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Broadband'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'License'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Requests'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Transfer'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Archive'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Report'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'AUC'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'FFE'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Machinery'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Vehicle'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Software'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Accessory'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Asset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'AssetModel'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Backup'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Category'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Comment'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Component'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Consumable'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Depreciation'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Field'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Fieldset'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Location'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'LocationUser'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Log'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Manufacturer'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Miscellanea'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Photo'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Property'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Role'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Status'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'Supplier'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);
        Permission::updateOrCreate([
            'role_id' => $temp->id,
            'model' => 'User'], [
            "Create" => 0,
            "update" => 0,
            "view" => 0,
            "delete" => 0,
            "archive" => 0,
            "transfer" => 0,
            "request" => 0,
            "spec_reports" => 0,
            "fin_reports" => 0,
        ]);

        // temporary end
    }

}
