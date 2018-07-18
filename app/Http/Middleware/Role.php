<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role {

    public function handle($request, Closure $next)
    {

        if ( Auth::guard('employers')->check() && Auth::guard('employers')->user()->isAdmin() )
        {
            return $next($request);
        }else{
        	 return abort(404);
        }

       

    }

}