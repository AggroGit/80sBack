<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\PayOut;

class jobSendMoney implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pay;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pay)
    {
        //
        $this->pay = PayOut::find($pay);
        //

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $transfer = $this->pay->user->Pay($this->pay->price_sended,$this->pay->purchase_id);
        $this->pay->stripe_payout_id = $transfer->id;
        $this->pay->save();
    }
}
