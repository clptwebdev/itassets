<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;
//Models
use App\Models\User;
use App\Models\FFE;

class FFEPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ffe;
    protected $user;
    public $path;
    
    public function __construct(FFE $ffe, User $user, $path)
    {
        $this->ffe = $ffe;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $ffe = $this->ffe;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('FFE.showPdf', compact('ffe', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
