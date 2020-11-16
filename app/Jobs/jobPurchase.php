<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\jobPurchase;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use App\Purchase;

class jobPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $purchase;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($purchase_id)
    {
        $this->purchase = Purchase::find($purchase_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // si hay menos de 3 intentos se cobra al cliente
        if($this->purchase->payment_tries<=3) {
          // si el cobro es exitoso
          if($this->purchase->CobrarCliente()){
            //
            $this->purchase->refresh();
            // se pone el purchase como como completado
            $this->purchase->status = "finished";
            // está completado
            $this->purchase->completed = true;
            // se lanzan los pagos
            $this->purchase->save();
            // pagamos
            $this->purchase->pagarComerciantes();
            //

          } else {
            //
            $this->purchase->payment_tries = $this->purchase->payment_tries+1;
            $this->purchase->nextTry = $next = now()->addDays(3);
            $this->purchase->save();
            // lanzamos otro intento
            jobPurchase::dispatch($this->purchase->id)->delay($next);
            // notificamos al usuario
            sendMail::dispatch(new BasicMail([
              "title" =>  "Su pago ha fallado. Por favor revise su método de pago a través de la App",
              "text"  => "Buenas ".$this->purchase->user->name.", Hemos intentado realizar un cobro en su tarjeta. Intento ".$this->purchase->payment_tries."/3. Se volverá a intentar el pago en 3 días"
            ]), $this->purchase->user->email);
          }

        }
    }
}
