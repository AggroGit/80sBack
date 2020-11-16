<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Chat;
use App\User;
use App\Report;

class ChatsController extends Controller
{
    // it sends the message
    public function send(int $chat_id,Request $request)
    {
      // validation
      if ($missings = $this->hasError($request->all(),'validation.sendChat')) {
        return $this->incorrect(0,$missings);
      }
      $message = new Message();
      // we create the message
      $message->createMessage($request,$chat_id);
      $message->refresh();
      return $this->correct(Message::without('user')->find($message->id));

    }

    public function checkChatExist($user_id)
    {
      // if user exists
      if($user = User::find($user_id)) {
        $chat = auth()->user()->business->chats()->whereHas('users', function ($users) use ($user_id) {
          $users->where('id',$user_id);
        })->first();
        if(!$chat) {
          return $this->incorrect(101);
        }
        return $this->correct($chat);
      }
      return $this->incorrect(3);
    }

    // chats list
    public function chats()
    {
      return $this->correct(auth()->user()->chats);
    }

    public function chat(int $chat_id)
    {
      return $this->correct(Chat::find($chat_id));
    }

    public function messages(int $chat_id)
    {
      $chat = Chat::find($chat_id);
      $chat->messages()->where('read',false)->update(['read'=>true]);
      return $this->correct($chat->messagesPaginated());
    }

    public function block($chat_id, Request $request)
    {
      // validation
      if ($missings = $this->hasError($request->all(),'validation.blockUser')) {
        return $this->incorrect(0,$missings);
      }
      // if exists
      if($user = User::find($request->user_id)) {

         $chat = Chat::find($chat_id);
         $chat->block($user);
         $chat->save();
         return $this->correct($chat);
      }
      return $this->incorrect(3);

    }

    public function unblock($chat_id, Request $request)
    {
      // validation
      if ($missings = $this->hasError($request->all(),'validation.blockUser')) {
        return $this->incorrect(0,$missings);
      }
      // if exists
      if($user = User::find($request->user_id)) {
         $chat = Chat::find($chat_id);
         $chat->block($user,false);
         $chat->save();
         return $this->correct($chat);
      }
      return $this->incorrect(3);
    }

    public function report($user_id, Request $request)
    {
      // validation
      if ($missings = $this->hasError($request->all(),'validation.report')) {
        return $this->incorrect(0,$missings);
      }
      // hacemos un report de un usuario a otro
      $report = new Report;
      $report->from_user_id = auth()->user()->id;
      $report->to_user_id = $user_id;
      $report->about = "chat";
      $report->message = $request->message?? "";
      $report->save();
      return $this->correct($report);
    }



}
