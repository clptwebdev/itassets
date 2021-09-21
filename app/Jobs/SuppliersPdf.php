<?php

namespace App\Jobs;

use App\Models\Supplier;
use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Storage;

class SuppliersPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $suppliers;
    protected $user;
    public $path;
    
    public function __construct($suppliers, $user, $path)
    {
        $this->suppliers = $suppliers;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $suppliers = $this->suppliers;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('suppliers.pdf', compact('suppliers', 'user'));
        $pdf->setPaper('a4', 'landscape');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";

    }
}
