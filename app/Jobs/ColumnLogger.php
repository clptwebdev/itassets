<?php

namespace App\Jobs;

use App\Models\Log;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Schema;

class ColumnLogger implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($exceptions, $model)
    {
        $this->exceptions = $exceptions;
        $this->model = $model;
        $this->modelName = strtolower(str_replace('App\\Models\\', '', get_class($this->model)));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //get column Names
        $columns = Schema::getColumnListing($this->model->getTable());
        $array = array_diff($columns, $this->exceptions);

        $changes = [];
        $updateSentence = [];
        foreach($array as $id => $column)
        {
            //checks if the columns have been edited if they have add it to the changes array
            if($this->model->isDirty($column))
            {
                array_push($changes, [$column => $this->model->$column]);
            }
        }
        foreach($changes as $id => $change)
        {
            foreach($change as $field => $item)
            {
                $data = 'Item Changed: ' . ucfirst($field) . ' Edited to ' . $item;
                array_push($updateSentence, $data);
            }
        }
        //create the sentence and create the log
        $sentence = implode(', ', $updateSentence);

        Log::create([
            'user_id' => auth()->user()->id,
            'log_date' => Carbon::now(),
            'loggable_type' => $this->modelName,
            'loggable_id' => $this->model->id,
            'data' => 'Update: ' . $sentence,
        ]);
    }

//    public function getResponse()
//    {
//        return $this->response;
//    }

}
