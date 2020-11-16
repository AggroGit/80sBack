<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\MessageEvent;
use App\Message;

class jobMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message,$user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($messageIn)
    {
        //
        $this->message = $messageIn;
        $this->user = auth()->user();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // socket
         
         // notifications
         // $users = \App\Chat::find($this->message->chat_id)
         //               ->users()
         //               ->where('users.id','!=', $this->user->id)
         //               ->get();
         // // notify users
         // foreach ($users as $user) {
         //     $user->send([
         //       "title"   => "Mensaje de ".$this->user->name,
         //       "message" => $this->message,
         //       "data"    => $this
         //     ]);
         // }
    }
}
