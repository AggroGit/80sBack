<?php

namespace App\Http\Middleware;

use Closure;

class isMyApp
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
        // cogemos el codigo de app, quitaos el presence
        // if(!$code = explode('-', $request->channel_name)) {
        //   if(!$code = explode('.', $code[1]) and !$code[0] == env('APP_ID',97)){
        //     return app()->call('App\Http\Controllers\Controller@incorrect',
        //                       ['code'=> 99]);
        //   }
        // }
        return $next($request);



    }
}
