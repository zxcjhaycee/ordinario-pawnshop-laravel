<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CombineAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$access)
    {
        // dd($access);
        if(in_array(Auth::user()->access,$access)){
            return $next($request);

        }
        return redirect()->route('dashboard');
    }
}
