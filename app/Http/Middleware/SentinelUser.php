<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;

class SentinelUser
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
        elseif(Sentinel::inRole('admin') || Sentinel::inRole('staff') || Sentinel::inRole('superadmin') || Sentinel::inRole('supervisor')
            || Sentinel::inRole('maintenance') || Sentinel::inRole('audit') || Sentinel::inRole('operator')|| Sentinel::inRole('mechanic') || Sentinel::inRole('autovalidate') || Sentinel::inRole('pointof'))
            return $next($request);
        return Redirect::route('dashboard');
    }
}
