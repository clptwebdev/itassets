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
        $name = $asset->model->name.'['.$asset->asset_tag.']' ?? $asset->asset_tag;
        $location = 'It has been assigned to '.$asset->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id'=>auth()->user()->id, 
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id, 
            'data'=> auth()->user()->name.' has added a new asset: '.$name.'. '.$location,
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
        $name = $asset->model->name.'['.$asset->asset_tag.']' ?? $asset->asset_tag;
        $location = 'It has been assigned to '.$asset->location->name ?? 'It has not been assigned to a location.';
        Log::create([
            'user_id'=>auth()->user()->id, 
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'asset',
            'loggable_id'=> $asset->id, 
            'data'=> auth()->user()->name.' has added a new asset: '.$name.'. '.$location,
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
        //
    }

    /**
     * Handle the assets "restored" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function restored(Asset $asset)
    {
        //
    }

    /**
     * Handle the assets "force deleted" event.
     *
     * @param  \App\Models\Asset  $assets
     * @return void
     */
    public function forceDeleted(Asset $asset)
    {
        //
    }
}
