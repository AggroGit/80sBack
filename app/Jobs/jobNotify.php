<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;
use App\Notification;

use App\Mail\BasicMail;
use App\Jobs\sendMail;

class jobNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user,$data,$extra;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user,$data,$extra=null)
    {
        //
        $this->user = $user;
        $this->data = $data;
        $this->extra = $extra;
        //
        $noti = new Notification($data);
        $noti->user_id = $user->id;
        $noti->save();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $m = [
            'notification'  => $this->data,
            'to'            => $this->user->device_token,
            'data'          => $this->extra,
        ];
        $c = new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'key='.env('FIREBASE_TOKEN',''),
            ]
        ]);
        $r = $c->post('https://fcm.googleapis.com/fcm/send',
            ['body' => json_encode($m)]
        );
        echo $r->getBody();
        $data = [
          "title"         => "PRUEBA",
          "logoInTitle"   => true,
          "text"          =>  $r->getBody(),

        ];
        // sendMail::dispatch(new BasicMail($data),'alejandro.ruesga97@gmail.com');


    }
}
