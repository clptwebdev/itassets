<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\AssetModel;
use App\Models\Log;
use Carbon\Carbon;

class AssetModelObserver {

    public function created(AssetModel $assetModel)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'assetModel',
            'loggable_id' => $assetModel->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " created a new Asset Model - {$assetModel->name}",
        ]);
    }

    public function updated(AssetModel $assetModel)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $assetModel);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(AssetModel $assetModel)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'assetModel',
            'loggable_id' => $assetModel->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " deleted Asset Model - {$assetModel->name}",
        ]);
    }

    public function restored(AssetModel $assetModel)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'assetModel',
            'loggable_id' => $assetModel->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " restored Asset Model - {$assetModel->name}",
        ]);
    }

    public function forceDeleted(AssetModel $assetModel)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'assetModel',
            'loggable_id' => $assetModel->id ?? 0,
            'data' => auth()->user()->name ?? "Unknown" . " permanently deleted Asset Model - {$assetModel->name}",
        ]);
    }

}
