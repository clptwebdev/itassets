<?php

namespace App\Observers;

use App\Models\Asset;
use App\Models\Log;
use Carbon\Carbon;

class AssetObserver
{
    /**
     * Handle the assets "created" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function created(Asset $asset)
    {
        $name = $asset->model->name ?? "Unknown".' ['.$asset->asset_tag.']' ?? $asset->asset_tag;
        $location = 'It has been assigned to '.$asset->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' has added a new asset: '.$name.'. '.$location,
        ]);
    }

    /**
     * Handle the assets "updated" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function updated(Asset $asset)
    {
        $name = $asset->model->name ?? "Unknown".' ['.$asset->asset_tag.']' ?? $asset->asset_tag;
        $location = 'It has been assigned to '.$asset->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' has added a new asset: '.$name.'. '.$location,
        ]);
    }

    /**
     * Handle the assets "deleted" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function deleted(Asset $asset)
    {
        $name = $asset->model->name ?? "Unknown".' ['.$asset->asset_tag.']' ?? $asset->asset_tag;
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' has placed the Asset: '.$name.' into the recycling bin',
        ]);
    }

    /**
     * Handle the assets "restored" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function restored(Asset $asset)
    {
        $name = $asset->model->name ?? "Unknown".' ['.$asset->asset_tag.']' ?? $asset->asset_tag;
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'loggable_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' has restored the Asset: '.$name,
        ]);
    }

    /**
     * Handle the assets "force deleted" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function forceDeleted(Asset $asset)
    {
        $name = $asset->model->name ?? "Unknown". ' ['.$asset->asset_tag.']' ?? $asset->asset_tag;
        Log::create([
            'user_id'=>auth()->user()->id ?? 0,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id ?? 0,
            'data'=> auth()->user()->name ?? "Unknown".' has permanently removed the Asset: '.$name,
        ]);
    }
}
