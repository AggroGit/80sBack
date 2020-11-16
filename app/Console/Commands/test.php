<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use App\User;

class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if($user = User::find(1)) {
          $data = [
            "title"         =>"Buenas, se ha suspendido su cuenta ",
            "logoInTitle"   => true,
            "text"          => "Por violar las leyes",
            "option"        => [
              'text'  =>  "Aceptar",
              'url'   =>  url('/')
            ]
          ];
          sendMail::dispatch(new BasicMail($data),'poropo97@gmail.com');
        }

    }
}
