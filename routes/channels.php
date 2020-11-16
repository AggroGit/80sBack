<?php

use Illuminate\Support\Facades\Broadcast;
use App\Chat;
use App\User;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// channel User
Broadcast::channel('{app_id}.User.{id}', function ($user,$app_id,$id) {
    //
    // echo "$app_id";
    // echo "id-> $id";
    // echo " user ->  $user->name";ç
     // echo $user->name;
    return $app_id == env('APP_ID',97) and auth()->user()->id == $user->id?
    auth()->user():false;
    // return true;
});

// channel Chat
Broadcast::channel('{app_id}.Business.{id}', function ($user,$app_id,$id) {
    return auth()->user();
});

// channel Chat
Broadcast::channel('{app_id}.Chat.{id}', function ($user,$app_id,$id) {
    //
    // echo "$app_id";
    // echo "id-> $id";
    // echo " user ->  $user->name";
    // echo $user->name;
    return ($app_id == env('APP_ID',97) and ($chat = Chat::find($id)) and $chat->conditions())?
    auth()->user() : false;
    // return true;
});
