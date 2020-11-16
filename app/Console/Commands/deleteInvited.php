<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;

class deleteInvited extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:invited';

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
        return User::where([
          ['invited',true],
          ['created_at', '<=' ,now()->subDays(3)]
        ])->delete();
    }
}
