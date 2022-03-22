<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() &&  (Auth::user()->level == 'Admin' || Auth::user()->level == 'Supervisor' || Auth::user()->level == 'WFC')) {
             return $next($request);
        }

        return redirect('unauthorized')->with('error','You lack the access rights');
    }
}