<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\User;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    //
    public $user;
    public $object;
    public $app_id;

    /**
     * the information and the user
     *
     * @return void
     */
    public function __construct($object, User $user=null)
    {
      $this->user   = $user?? auth()->user();
      $this->object = $object;
      $this->app_id = env('APP_ID', '97');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $id = $this->user->id?? 0;
      return new PresenceChannel("$this->app_id.User.$id");
    }


    // public function broadcastWith()
    // {
    //   return [
    //     "type" => $this->getName()?? "undefined",
    //     "data" => $this->object,
    //
    //   ];
    // }

    // the conditions for the suscription of the channel
    public function broadcastWhen()
    {
      return true;
      // return $this->user->isConected()? true:false;
    }

    // get the name of a class
    public function getName() {
      $path = explode('\\', get_class($this->object["data"]));
      return array_pop($path);
    }



}
