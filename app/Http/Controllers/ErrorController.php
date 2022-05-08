<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ErrorController extends Controller {

    public static function forbidden($link, $message)
    {

        return view('errors.403', [
            "link" => $link ?? '/',
            'message' => $message ?? 'Unauthorised for this action.',

        ]);

    }

}
