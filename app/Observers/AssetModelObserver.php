<?php

namespace App\Observers;

use App\Models\AssetModel;
use App\Models\Log;
use Carbon\Carbon;

class AssetModelObserver
{
    
    public function created(AssetModel $assetModel)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'assetModel',
            'loggable_id'=> $assetModel->id,
            'data'=> auth()->user()->name." created a new Asset Model - {$assetModel->name}"
        ]);
    }

    public function updated(AssetModel $assetModel)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'assetModel',
            'loggable_id'=> $assetModel->id,
            'data'=> auth()->user()->name." updated Asset Model - {$assetModel->name}"
        ]);
    }

    public function deleted(AssetModel $assetModel)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'assetModel',
            'loggable_id'=> $assetModel->id,
            'data'=> auth()->user()->name." deleted Asset Model - {$assetModel->name}"
        ]);
    }

    public function restored(AssetModel $assetModel)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'assetModel',
            'loggable_id'=> $assetModel->id,
            'data'=> auth()->user()->name." restored Asset Model - {$assetModel->name}"
        ]);
    }

    public function forceDeleted(AssetModel $assetModel)
    {
        Log::create([
            'user_id'=>auth()->user()->id,
            'log_date'=> Carbon::now(),
            'loggable_type'=> 'assetModel',
            'loggable_id'=> $assetModel->id,
            'data'=> auth()->user()->name." permanently deleted Asset Model - {$assetModel->name}"
        ]);
    }
}
