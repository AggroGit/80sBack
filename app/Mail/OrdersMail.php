<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrdersMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $orders,$purchase,$data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orders,$purchase,$data = null)
    {
        //
        $this->orders     = $orders;
        $this->purchase   = $purchase;
        $this->data       = $data;


    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@merco.app')
                    ->subject($this->data['title']?? "Mensaje de Merco")
                    ->view('mails.orders')
                    ->with([
                      "purchase"  => $this->purchase,
                      "orders"    => $this->orders
                    ])->with($this->data);
    }
}
