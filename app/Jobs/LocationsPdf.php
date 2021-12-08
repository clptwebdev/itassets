<?php

namespace App\Jobs;

use App\Models\Location;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class LocationsPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $locations;
    protected $user;
    public $path;
    
    public function __construct($locations, $user, $path)
    {
        $this->locations = $locations;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $locations = $this->locations;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('locations.pdf', compact('locations', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
        
        
    }
}
