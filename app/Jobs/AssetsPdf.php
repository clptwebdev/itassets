<?php

namespace App\Jobs;

use App\Models\Asset;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class AssetsPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $assets;
    protected $user;
<<<<<<< HEAD
    protected $path;
    
=======
    public $path;

>>>>>>> 8332e75804bf62be176a84b85d782116e314e06f
    public function __construct($assets, $user, $path)
    {
        $this->assets = $assets;
        $this->user = $user;
        $this->path = $path;
    }
    public function handle()
    {
        $assets = $this->assets;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('assets.pdf', compact('assets', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
