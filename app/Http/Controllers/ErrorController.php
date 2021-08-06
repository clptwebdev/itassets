<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    //

    public function forbidden($type, $id, $method){
        return view('errors.403', compact('type', 'id', 'method'));
    }
}
