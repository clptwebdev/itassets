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
use App\Models\AUC;

class AUCPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $auc;
    protected $user;
    public $path;
    
    public function __construct(AUC $auc, User $user, $path)
    {
        $this->auc = $auc;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $auc = $this->auc;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('AUC.showPdf', compact('auc', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
