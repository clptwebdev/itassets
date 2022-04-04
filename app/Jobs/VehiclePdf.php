<?php

namespace App\Jobs;

use App\Models\Software;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use PDF;

class VehiclePdf implements ShouldQueue {

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vehicle;
    protected $user;
    public $path;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Vehicle $vehicle, User $user, $path)
    {
        $this->vehicle = $vehicle;
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
        $vehicle = $this->vehicle;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('vehicle.showPdf', compact('vehicle', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/" . $path . ".pdf", $pdf->output());
        $this->path = "";
    }

}
