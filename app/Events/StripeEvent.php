<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Message;
use App\User;

class StripeEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    //
    public $business;
    public $app_id;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $message)
    {
      $this->business = $message;
      $this->app_id = env('APP_ID', '97');
    }



    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      $id = $this->business;
      return new PresenceChannel("$this->app_id.Business.$id");
    }

    // the conditions for the suscription of the channel
    public function broadcastWhen()
    {
      // if the chat is open and the current user is in the chat
      return true;
    }





}
