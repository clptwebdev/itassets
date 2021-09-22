<?php

namespace App\Jobs;

use App\Models\AssetModel;
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

class AssetModelPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $user;
    public $path;
    
    public function __construct(AssetModel $model, User $user, $path)
    {
        $this->model = $model;
        $this->user = $user;
        $this->path = $path;
    }

    public function handle()
    {
        $asset = $this->model;
        $user = $this->user;
        $path = $this->path;
        $pdf = PDF::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])->loadView('asset-models.showPdf', compact('model', 'user'));
        $pdf->setPaper('a4', 'portrait');
        Storage::put("public/reports/".$path.".pdf", $pdf->output());
        $this->path = "";
    }
}
