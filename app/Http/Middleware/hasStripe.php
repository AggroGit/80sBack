<?php

namespace App\Http\Middleware;

use Closure;

class hasStripe
{
    /**
     * Verify if the current user is logged and if he has stripe account
     * in case of not, we create one
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!auth()->user()->hasStripeId()) {
            auth()->user()->createAsStripeCustomer();
            auth()->user()->save();
        }
        return $next($request);
    }
}
