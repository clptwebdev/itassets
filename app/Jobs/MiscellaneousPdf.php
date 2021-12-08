<?php

namespace App\Jobs;

use App\Models\Miscellanea;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class MiscellaneousPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $miscellaneous;
    protected $user;
    public $path;
    
    public function __construct($miscellaneous, $user, $path)
    {
        $this->miscellaneous = $miscellaneous;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $miscellaneous = $this->miscellaneous;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('miscellanea.pdf', compact('miscellaneous', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
        
        
    }
}
