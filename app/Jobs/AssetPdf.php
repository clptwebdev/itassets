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

class AssetPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $asset;
    protected $user;
    public $path;
    
    public function __construct(Asset $asset, $user, $path)
    {
        $this->asset = $asset->id;
        $this->user = $user;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $asset = Asset::find($this->asset);
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('assets.showPdf', compact('asset', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
