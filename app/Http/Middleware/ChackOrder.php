<?php

namespace App\Http\Middleware;

use Closure;
use App\Order;

class ChackOrder
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
        if(!$order = Order::find($request->order_id)){
          return app()->call('App\Http\Controllers\OrderController@incorrect',
                              ['code'=> 1100]);
        }

        // la orden ha de ser del cliente, del propietario o ser usuario admin
        if($id = $order->user_id and $id == auth()->user()->id or $order->product->business->user->id == auth()->user()->id or auth()->user()->type === "admin") {
          return $next($request);
        }
        else {
          return app()->call('App\Http\Controllers\OrderController@incorrect',
                              ['code'=> 1102]);
        }

    }
}
