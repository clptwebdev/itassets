<?php

namespace App\Jobs;

use App\Models\Manufacturer;
use App\Models\User;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class ManufacturerPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $manufacturer;
    protected $user;
    public $path;
    
    public function __construct(manufacturer $manufacturer, User $user, $path)
    {
        $this->manufacturer = $manufacturer;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $manufacturer = $this->manufacturer;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('manufacturers.showPdf', compact('manufacturer', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
