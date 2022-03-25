<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller {

    public static function forbidden($link, $message)
    {
        return view('errors.403', [
            "link" => $link ?? '/',
            'message' => $message ?? 'Unauthorised for this action.',

        ]);
    }

}
