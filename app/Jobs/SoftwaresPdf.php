<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class SoftwaresPdf implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $softwares;
    protected $user;
    protected $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($softwares, User $user, $path)
    {
        $this->softwares = $softwares;
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
        $softwares = $this->softwares;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('software.pdf', compact('softwares', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/" . $path . ".pdf", $pdf->output());
        $this->path = "";
    }

}
