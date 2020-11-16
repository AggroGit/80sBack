<?php

namespace App\Http\Middleware;

use Closure;

class notInvited
{
    /**
     * if the user is not invited error 5
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth()->user()->invited) {
          return app()->call('App\Http\Controllers\ChatsController@incorrect',
                              ['code'=> 5]);
        }
        return $next($request);
    }
}
