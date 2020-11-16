<?php

namespace App\Http\Middleware;

use Closure;
use App\Chat;

class CheckChat
{
    /**
     * here we check if the user can access to this chat.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // check you have the id
        if (!$id = $request->chat_id)
        return app()->call('App\Http\Controllers\ChatsController@incorrect',
                            ['code'=> 100]);
        // check the chat exists
        if (!$chat = Chat::find($id))
        return app()->call('App\Http\Controllers\ChatsController@incorrect',
                            ['code'=> 101]);
        // check the chat is open
        if (!$chat->open)
        return app()->call('App\Http\Controllers\ChatsController@incorrect',
                            ['code'=> 102]);
        // check if the user is on the chat
        if (!$chat->isUser(auth()->user()))
        return app()->call('App\Http\Controllers\ChatsController@incorrect',
                            ['code'=> 4]);


        return $next($request);
    }
}
