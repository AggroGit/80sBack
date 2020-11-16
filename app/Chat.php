<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Sockeable;
use App\User;

class Chat extends Model
{
    use Sockeable;
    protected $with = ['lastMessage','users'];
    protected $appends = ['otherIsBlocked'];


    // messages of the chat
    public function messages()
    {
       return $this->hasMany('App\Message')
                   ->orderBy('created_at','desc');
    }

    public function getotherIsBlockedAttribute()
    {
      if(auth()->user()) {
        if($user = $this->getTheOtherUser()) {
          return $user->pivot->blocked? true:false;
        }
      }
      return false;
    }

    public function getTheOtherUser()
    {
      if(auth()->user()) {
        if($user =$this->users()->where('user_id','!=', auth()->user()->id)->first()) {
          return $user;
        }
      }
      return false;

    }

    // the business of a chat
    public function business()
    {
      return $this->belongsTo('App\Business');
    }

    // the messages of the chat paginated
    public function messagesPaginated()
    {
      return $this->messages()
                  ->paginate(100);
    }

    // users in the chat
    public function users()
    {
      return $this->belongsToMany('App\User', 'chats_users')->withPivot('blocked');
    }

    // the last message sended in the chat
    public function lastMessage()
    {
      return $this->messages()
                  ->take(1);
    }

    // delete user
    public function deleteUser(User $user)
    {
      $this->users()->detach($user);
      $this->save();
    }


    // check if is user in a chat
    public function isUser(User $user)
    {
      if ($this->users()->find($user->id)) {
        return true;
      }
      return false;
    }

    public function quitUser(User $user)
    {
      if($this->isUser($user)) {
        $this->users()->detach($user);
        return $this->save();
      }
    }

    // we pass the user and if we want to quit the hidden we pass the second param
    public function hidden(User $user,$bool = true)
    {
      if($this->isUser($user)) {
        $this->users()->updateExistingPivot($user->id,[
          "hidden" => $bool
        ]);
        return $this->save();
      }
    }

    // we pass the user and if we want to quit the hidden we pass the second param
    public function block(User $user,$bool = true)
    {
      if($this->isUser($user)) {
        $this->users()->updateExistingPivot($user->id,[
          "blocked" => $bool
        ]);
        return $this->save();
      }
    }

    public function addUser(User $user)
    {
      $this->save();
      if (!$this->isUser($user)) {
        $this->users()->save($user);
        return $this->save();
      }
      return false;
    }

    // public function getOwnerBusinessAttribute()
    // {
    //   return $this->business->user;
    // }

    // conditions for the connection to the chat
    public function conditions()
    {
      // is the chat open
      // is the user in chat
      // is the user not blocked PENDING
      return ($this->open);

    }

    public function delete()
    {
        // delete all related messages
        foreach ($this->users as $user) {
          $this->quitUser($user);
        }
        $this->messages()->delete();
        return parent::delete();
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Id'         =>  'id',
          'chat de' =>  [
            'model_name' => 'business',
            'select' => Business::all(),
            'show'       => 'name',
            'url'        => "admin/business/edit"
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'remove'  => true,
        ],
        'singular' => 'chat',
        'name'  => 'Chats'
      ];

    }








}
