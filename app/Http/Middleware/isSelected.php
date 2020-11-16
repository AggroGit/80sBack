<?php

namespace App\Http\Middleware;

use Closure;

use App\Order;

class isSelected
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
        // si no existe error
        if(Order::find($request->order_id)->status !== "selected"){
          return app()->call('App\Http\Controllers\OrderController@incorrect',
                              ['code'=> 1101]);
        }
        return $next($request);
    }
}
