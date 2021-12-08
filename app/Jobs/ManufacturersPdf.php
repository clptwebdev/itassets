<?php

namespace App\Jobs;

use App\Models\Manufacturer;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class ManufacturersPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $manufacturers;
    protected $user;
    public $path;
    
    public function __construct($manufacturers, $user, $path)
    {
        $this->manufacturers = $manufacturers;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $manufacturers = $this->manufacturers;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('Manufacturers.pdf', compact('manufacturers', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
        
        
    }
}
