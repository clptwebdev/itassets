<?php

namespace App\Http\Middleware;

use App\Http\Controllers\ErrorController;
use Closure;
use Illuminate\Http\Request;

class CheckUserRole {

    public function handle($request, Closure $next)
    {

        if($request->user()->role_id !== 7 && $request->user()->role_id !== 0)
        {
            return $next($request);
        } else
        {
            ;

            return redirect('/dashboard')->with('danger_message', 'No Permission to Access this Area.');
        }

    }

}
