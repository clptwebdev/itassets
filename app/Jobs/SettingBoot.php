<?php

namespace App\Jobs;

use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SettingBoot implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        //sets notifications for broadband at 30 days
        Setting::updateOrCreate([
            'name' => 'broadband_expiry'], [
            'value' => 30,
            'priority' => 1,
        ]);
        //amount of money until an asset is ignored
        Setting::updateOrCreate([
            'name' => 'asset_threshold'], [
            'value' => 200,
            'priority' => 1,
        ]);

    }

}
