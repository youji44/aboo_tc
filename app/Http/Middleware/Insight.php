<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use Illuminate\Support\Facades\Session;

class Insight
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
        Session::put('insight','yes');
        return $next($request);
    }
}
