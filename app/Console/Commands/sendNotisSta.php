<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\StaNoti;

class sendNotisSta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendNotisSta:send';

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
      // take the notis not sended
      $notis = StaNoti::where([
        ['sended',false],
        ['send_at','<=',now()]
      ])->get();

      foreach ($notis as $noti) {
        echo "enviando..";
        $noti->send();
      }
      return 0;
    }
}
