<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
        return $next($request)
            ->header('Acess-Control-Allow-Origin', "*")
            ->header('Acess-Control-Allow-Methods', "POST,DELETE,GET,PUT,OPTIONS")
            ->header('Acess-Control-Allow-Headers', "Accept,Authorization,Content-Type")
            
        ;
    }
}
