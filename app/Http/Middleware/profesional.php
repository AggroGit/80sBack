<?php

namespace App\Http\Middleware;

use Closure;
use App\Business;

class profesional
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

        // si es super admin sí puede editar
        if (auth()->user()->type == "admin") {
          return $next($request);
        }
        // que sea administrador
        if(!$business->isAdmin(auth()->user())) {
          return app()->call('App\Http\Controllers\profesionalController@incorrect',
                              ['code'=> 4]);
        }
        return $next($request);
    }
}
