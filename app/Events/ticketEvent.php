<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Purchase;

class ticketEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $purchase;
    public $app_id;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Purchase $purchaseIn)
    {
        //
        $this->purchase = Purchase::with('user')->find($purchaseIn->id);
        $this->app_id = env('APP_ID', '97');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
      return new PresenceChannel("$this->app_id.Business.1");
    }

    public function broadcastWhen()
    {
      return true;
    }
}
