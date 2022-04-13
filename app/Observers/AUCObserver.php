<?php

namespace App\Observers;

use App\Jobs\ColumnLogger;
use App\Models\AUC;
use App\Models\Log;
use Carbon\Carbon;

class AUCObserver {

    public function __construct()
    {
        $this->user = $this->user . 'An Unauthorized User';
    }

    public function created(AUC $auc)
    {

        $location = 'It has been assigned to ' . $auc->location->name ?? 'It has not been assigned to a location.';

        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'AUC',
            'loggable_id' => $auc->id ?? 0,
            'data' => $this->user . 'A Un-Authorised User has added a new AUC: ' . $auc->name . '. ' . $location,
        ]);
    }

    public function updated(AUC $auc)
    {
        /////////////////////////////////////////////
        /////////// Dynamic Column changes///////////
        /////////////////////////////////////////////
        // Ignored these Table names
        $exceptions = ['id', 'created_at', 'updated_at'];
        ColumnLogger::dispatchSync($exceptions, $auc);
        /////////////////////////////////////////////
        //////// Dynamic Column changes End//////////
        /////////////////////////////////////////////
    }

    public function deleted(AUC $auc)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'log_date' => Carbon::now(),
            'loggable_type' => 'AUC',
            'loggable_id' => $auc->id ?? 0,
            'data' => $this->user . 'A Un-Authorised' . ' has placed the AUC: ' . $auc->name . ' into the recycling bin',
        ]);
    }

    public function restored(AUC $auc)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'AUC',
            'loggable_id' => $auc->id ?? 0,
            'data' => $this->user . 'A Un-Authorised' . ' has restored the AUC: ' . $auc->name,
        ]);
    }

    public function forceDeleted(AUC $auc)
    {
        Log::create([
            'user_id' => auth()->user()->id ?? 0,
            'loggable_type' => 'AUC',
            'loggable_id' => $auc->id ?? 0,
            'data' => $this->user . 'A Un-Authorised' . ' has permanently removed the AUC: ' . $auc->name,
        ]);
    }

}
