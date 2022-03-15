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
use App\Models\Property;

class PropertyPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $property;
    protected $user;
    public $path;
    
    public function __construct(Property $property, User $user, $path)
    {
        $this->property = $property;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $property = $this->property;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('property.showPdf', compact('property', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
