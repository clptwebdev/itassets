<?php

namespace App\Jobs;

use App\Models\Broadband;
use App\Models\License;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class licensePdf implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $license;
    protected $user;
    public $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(License $license, User $user, $path)
    {
        $this->license = $license;
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
        $license = $this->license;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('licenses.showPdf', compact('license', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/" . $path . ".pdf", $pdf->output());
        $this->path = "";
    }

}
