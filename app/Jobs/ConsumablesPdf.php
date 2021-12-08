<?php

namespace App\Jobs;

use App\Models\Consumable;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class ConsumablesPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $consumables;
    protected $user;
    public $path;
    
    public function __construct($consumables, $user, $path)
    {
        $this->consumables = $consumables;
        $this->user = $user;
        $this->path = $path;
    }
    public function handle()
    {
        $consumables = $this->consumables;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('consumable.pdf', compact('consumables', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
        
        
    }
}
