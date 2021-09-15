<?php

namespace App\Jobs;

use App\Models\Accessory;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class AccessoriesPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $accessories;
    protected $user;
    public $path;
    
    public function __construct($accessories, $user, $path)
    {
        $this->accessories = $accessories;
        $this->user = $user;
        $this->path = $path;
    }
    public function handle()
    {
        $accessories = $this->accessories;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('accessory.pdf', compact('accessories', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
        
        
    }
}
