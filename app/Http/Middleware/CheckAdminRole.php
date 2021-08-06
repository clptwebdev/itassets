<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $role = auth()->user()->role_id;
        if($role <= 2 && $role !=0){
            return $next($request);
        }else{
            return redirect(route('errors.forbidden', ['area', $request->path(), 'view']));
        }        
    }
}
