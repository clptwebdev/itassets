<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if($role == 0){
            if($request->user()->role_id > 0){
                return $next($request);
            }else{
                return redirect('/permissions');
            }
        }else{
            if ($request->user()->role_id == $role) {
                // Redirect...
                return $next($request);
            }else{
                return redirect('/permissions');
            }
        }

        
    }
}
