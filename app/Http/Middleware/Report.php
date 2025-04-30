<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use Illuminate\Support\Facades\Session;

class Report
{
    /**
     * Handle an incoming request
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check())
            return Redirect::route('login')->with('info', 'You must be logged in!');
        else
            return $next($request);
    }
}
