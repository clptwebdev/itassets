<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Archive;

class ArchiveController extends Controller
{
    
    public function index(){
        $archives = Archive::all();
        return view('archives.view', compact('archives'));
    }

}
