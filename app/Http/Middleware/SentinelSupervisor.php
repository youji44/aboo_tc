<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;

class SentinelSupervisor
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
        if(!Sentinel::check())
            return Redirect::route('login')->with('info', 'You must be logged in!');
        elseif(Sentinel::inRole('admin') || Sentinel::inRole('superadmin') || Sentinel::inRole('supervisor') || Sentinel::inRole('autovalidate'))
            return $next($request);
        return Redirect::route('dashboard')->with('info', 'You must be logged in with Admin Role!');
    }
}
