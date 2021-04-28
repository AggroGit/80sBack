<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Jobs\jobSendMoney;
use App\Jobs\jobPurchase;
use App\Mail\OrdersMail;
use App\Mail\BasicMail;
use App\Jobs\sendMail;
use App\Business;
use App\Message;
use App\Scratch;
use App\PayOut;
use App\User;


class Purchase extends Model
{
    protected $with = ["orders",'discount','user'];
    //
    //
    protected $fillable = [
      'user_id', 'total_price', 'stripe_payment_id', "birthday",'discount_id','10_buys_discount','percentage_dicount', 'take_away', 'num_table',"type",'num_people','num_table','pay_method','comments','reserve_at'
    ];

    protected $casts = [
        'completed'   => 'boolean',
        'birthday'    => 'boolean',
        'take_away'   => 'boolean'
    ];

    protected $table = 'purchase';
    // the orders
    public function orders()
    {
      return $this->hasMany('App\Order');//->where('business_id',$business_id);
    }
    //
    public function pendingOrders()
    {
      $this->orders()->where('status','pending');
    }
    //
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    //
    public function discount()
    {
      return $this->belongsTo('App\Discount');
    }

    // payouts  of the purchase
    public function payOuts()
    {
       return $this->hasMany('App\PayOut');
    }

    // payouts  of the purchase
    public function getNumProductsAttribute()
    {
       return $this->orders()->count();
    }

    public function getEstadoPedidoAttribute()
    {
      $retorno = "desconocido";
      switch ($this->status) {
        case 'pending':
          $retorno = "Pendiente";
          break;
        case 'coming':
          $retorno = "De camino";
          break;
        case 'delivered':
          $retorno = "Finalizado";
          break;

        default:
          $retorno = "Desconocido";
          // code...
          break;
      }
      return $retorno;
    }


    // when the order is created
    public function mails()
    {
      // para el negocio

      $discount = $this->discount_id == null?
      "" : ("con un descuento aplicado del ".$this->discount->percentage_dicount.'%');
      $llevar = "";
      if($this->type == "domicilio")
        $llevar = "a domicilio";
      if($this->type == "llevar")
        $llevar = "para llevar";
      if($this->type == "en_restaurante")
        $llevar = "para comer en restaurante";

      $user = auth()->user();
      $data = [
        "title"       => "Nuevo Pedido $llevar con identificador $this->id",
        "logoInTitle" =>  true,
        "text"        => "Nuevo pedido $llevar de usuario con nombre $user->name a gestionar $discount. El identificador de pedido es $this->id",
        "ticket"      =>  "Resumen del Pedido (para ver descuentos ir al administrador de pedidos)",
        "option"      => [
          "text"  => "Abrir pedido",
          "url" => url('admin/purchase/edit/'.$this->id)
        ]
      ];
      sendMail::dispatch(new OrdersMail($this->orders,$this,$data),Business::find(1)->email);
      // cliente
      $llevar = $this->take_away? "para llevar ":"";
      $discount = $this->discount_id == null?
      "" : ("con un descuento aplicado del ".$this->discount->percentage_dicount.'%');
      $data = [
        "title"       => "Resumen de tu Pedido $llevar",
        "logoInTitle" =>  true,
        "text"        => "Puedes ver más detalles a través de la app",
        "ticket"      =>  "Tu Pedido",
      ];
      sendMail::dispatch(new OrdersMail($this->orders,$this,$data),$this->user->email);

    }

    public function notifyCancel($business)
    {
      $chat = $business->giveMeOrCreateChatWith($this->user);
      $message =  "❌ Se ha cancelado su pedido 000$this->id de $business->name ";
      // send message to chat
      $new = new Message();
      $new->chat_id = $chat->id;
      $new->message = $message;
      $new->user_id = $business->user_id;
      $new->save();
      $this->user->send([
        "title"   => 'Información sobre su pedido',
        "body"    => $message,
        "sound"   => "default",
        "type"    => "purchase"
      ]);

      $dataBusiness = [
        "title"       => "Pedido cancelado de ",
        "logoInTitle" =>  true,
        "text"        => $message,
      ];
      sendMail::dispatch(new BasicMail($dataBusiness),$business->user->email);
      sendMail::dispatch(new BasicMail($dataBusiness),$this->user->email);
    }

    public function recalculateTotalPrice()
    {
      $this->total_price = $this->orders()->where('status','pending')->sum('price');
      if($this->total_price == 0 and $this->orders()->count()) {
        $this->status = "canceled";
      }
      $this->save();
    }

    // se llama cuando se quiere cobrar al ciente
    public function CobrarCliente()
    {

        try {
          $price = intval($this->total_price*100);
          // ejecutamos un cobro
          $charge = $this->user->charge($price,$this->user->defaultPaymentMethod()->id);
        } catch (\Exception $e) {
          return false;
        }
        // get the id
        $charge_id =  $charge->asStripePaymentIntent()->id;
        $this->stripe_payment_id = $charge_id;
        // get the commisions
        $stripeComission = $this->user->getStripeCommisionFromCharge($charge->asStripePaymentIntent());
        // $ourComission = round($stripeComission*0.3,2);
        //
        $this->stripe_commisions = $stripeComission;
        // $this->merco_commisions = $ourComission;
        //
        $this->save();
        return true;

    }

    public function getTotalPurchaseAttribute()
    {
      return $this->total_price - $this->stripe_commisions;
    }

    public function getDirectionClientAttribute()
    {
      return $this->user->direction;
    }

    public function sendChat()
    {
      // cogemos las tiendas agrupadas por las ordenes y enviamos un mensaje al chat de que ha hecho un pedido
      $orders = $this->orders->groupBy('business_id');
      foreach ($orders as $key => $orderss) {
        if($business = Business::find($key)) {
          $chat = $business->giveMeOrCreateChatWith(auth()->user());
          $num = $orderss->count();
          $message = new Message();
          $message->user_id = $business->user->id;
          $pago = $this->pay_in_hand? "pago en mano" : "pago con tarjeta a través de la app 💳";
          $message->message = " 🆕 Nuevo Pedido de $num productos, $pago";
          $message->chat_id = $chat->id;
          $message->save();
        }
      }
    }

    // cuanto de comisión corresponde repsecto el total
    public function getRelativeCommision($total,$withCommsions,$price)
    {
      return round($price - (round(($withCommsions/$total)*$price,2)),2);
    }

    // se lanzan los pagos a comerciantes
    public function pagarComerciantes()
    {
      // seleccionamos las ordenes y las separamos por business
      $orders = $this->orders()->where('status','completed')->get()->groupBy('business_id');
      // cogemos el total de la orden y sus comisiones
      $total_commisions = ($this->stripe_commisions + $this->merco_commisions);
      $priceReal = $this->total_price - $total_commisions;
      // las recorremos
      foreach ($orders as $key => $orders) {
        if($business = Business::find($key)) {
          // calculamos sus precios y comisiones relativos
          $priceBusiness = $this->orders()->where('business_id',$key)->sum('price');
          $relativeComision = $this->getRelativeCommision($this->total_price,$priceReal,$priceBusiness);
          //
          $toSent = round($priceBusiness-$relativeComision,2);
          // date
          $when = now()->addDays(7);
          // creamos el payout y lo programamos
          $pay = new PayOut([
            "price_sended" => $toSent,
            "comision"     => $relativeComision,
            "user_id"      => $business->user->id,
            "purchase_id"  => $this->id,
            "business_id"  => $business->id,
            "money_send_at"=> $when
          ]);
          $pay->save();
          jobSendMoney::dispatch($pay->id)->delay($when);

        }
      }
    }

    // si la compra es superior a 10€ se le desbloquea un rasca y guaña
    public function PointsIfStaPerpetua()
    {
      if($this->total_price >= 10) {
        $this->user->giveRascaYGuaña();
      }
    }

    // las marca como completada
    public function checkIfAllCompleted()
    {

      $this->completed = true;
      $this->status = "finished";
      $this->save();

    }


    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'identificador' => 'id',
          'Tipo' => 'type',
          'Hora de reserva' => 'reserve_at',
          'Comentario' => 'comments',
          'Creado el'  => 'created_at',
          'Estado'  => 'EstadoPedido',
          'Cobrado'  => 'total_price',
          'Comisión Stripe' => 'stripe_commisions',
          'Total Resultante'   => 'totalpurchase',
          'Dirección' => 'DirectionClient',
          'Numero de mesa' => 'num_table',
          'Numero de gente' => 'num_people',
          'Usuario' => [
            'model_name' => 'user',
            'select'     => User::all(), // data al seleccionar en crear
            'show'       => 'name',
            'multiple'   => false,
            'url'        => "admin/user/edit"
          ],

        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
        ],
        'singular' => 'purchase',
        'name'  => 'Pedidos',

      ];

    }





}
