<?php

namespace App\Jobs;

use App\Models\AUC;
use App\Models\Software;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class SoftwarePdf implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $software;
    protected $user;
    public $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Software $software, User $user, $path)
    {
        $this->software = $software;
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
        $software = $this->software;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('software.showPdf', compact('software', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/" . $path . ".pdf", $pdf->output());
        $this->path = "";
    }

}
