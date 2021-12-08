<?php

namespace App\Jobs;

use App\Models\Miscellanea;
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

class MiscellaneaPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $miscellanea;
    protected $user;
    public $path;
    
    public function __construct(miscellanea $miscellanea, User $user, $path)
    {
        $this->miscellanea = $miscellanea;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $miscellanea = $this->miscellanea;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('miscellanea.showPdf', compact('miscellanea', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
