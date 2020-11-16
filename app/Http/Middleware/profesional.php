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

          // que el usuario sea de tipo profesional
          if (!(auth()->user()->type == "business" or auth()->user()->type == "admin")) {
            return app()->call('App\Http\Controllers\profesionalController@incorrect',
                                ['code'=> 4]);
          }
          // que el negocio exista
          if(!$business = Business::find($request->business_id)) {
            return app()->call('App\Http\Controllers\profesionalController@incorrect',
                                ['code'=> 804]);
          }
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
