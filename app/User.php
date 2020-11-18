<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Malhal\Geographical\Geographical;
use Illuminate\Support\Facades\Mail;
use Laravel\Passport\HasApiTokens;
use Laravel\Cashier\Billable;
use App\Mail\BasicMail;
use App\Traits\Sockeable;
use App\Traits\PayStripe;
use App\Traits\Perpetua;
use App\Traits\Notify;
use App\Jobs\sendMail;
use App\Association;
use Carbon\Carbon;
use App\Purchase;


class User extends Authenticatable
{
    use HasApiTokens, Sockeable, Notify, Billable, PayStripe, Geographical,Perpetua;
    //
    protected static $kilometers = true;
    //
    protected $with = ['business','notifications','image'];
    protected $appends = ['loggedSocial'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'admin', 'password', 'phone', 'longitude', 'latitude', 'direction', 'device_token', 'birthday'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','temporal_token', 'social_token', 'social_name'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'invited'           => 'boolean'
    ];

    // the chats of the user
    public function chats()
    {
      return $this->belongsToMany('App\Chat','chats_users')
                  ->where([
                    // ['blocked',false],
                    ['hidden',false]
                  ])->orderBy('updated_at','desc');
    }

    // si el usuario es profesional tendrá un negocio
    public function business()
    {
      return $this->hasOne('App\Business');
    }

    public function purchases()
    {
      return $this->hasMany('App\Purchase');
    }

    // the association of the user
    public function association()
    {
      return $this->belongsTo('App\Association');
    }

    // the chat of the message
    public function image()
    {
      return $this->belongsTo('App\Image');
    }

    // All the chats of the User
    public function allChats()
    {
      return $this->belongsToMany('App\Chat','chats_users')->orderBy('updated_at','desc');
    }

    // all the orders opf the users
    public function orders()
    {
       return $this->hasMany('App\Order');
    }

    //
    public function notifications()
    {
       return $this->allNotifications()->where('read',false);
    }

    //
    public function allNotifications()
    {
       return $this->hasMany('App\Notification')->orderBy('created_at','DESC');
    }

    public function reports()
    {
       return $this->hasMany('App\Report')
                   ->orderBy('created_at','desc');
    }

    // the entire shoppingCart
    public function completeShoppingCart()
    {
      return [
        "orders"        =>  auth()->user()->shoppingCart,
        "totalPrice"    =>  round(auth()->user()->shoppingCart->sum('price'),2),
        "number"        =>  auth()->user()->shoppingCart->count(),
        "num_business"  =>  auth()->user()->shoppingCart->groupBy('product.business_id')->count(),
        "url"           =>  $this->generateUrlRoute(auth()->user()->shoppingCart()->get())
      ];
    }


    // al the orders pendings
    public function shoppingCart()
    {
      return $this->orders()->where('status','selected');
    }

    public function pendings()
    {
      return $this->orders()->where('status','pending');
    }

    // return the cards of the user as stripe
    public function cards()
    {
        $cards = [];
        foreach ($this->paymentMethods() as $card) {
          $cards [] = $card->asStripePaymentMethod();
        }
        return $cards;
    }

    // in every app you have to specify the requeriments of the user to recive money
    public function canReciveMoney()
    {
      if ($this->type == "business" and $this->stripe_reciver_id !== null) {
        return true;
      }
      return false;
    }

    // if the user is not logged
    public function isInvited()
    {
        return $this->invited;
    }

    public function getloggedSocialAttribute()
    {
      return( $this->social_name==null)? false:true;
    }


    /**
     * it generates the token. And we return the token + the user info
     *
     * @return Array
     */
    public function creteTokenUser()
    {
        // llamamos al trait de passport
        $tokenResult = $this->createToken('Personal Access Token');
        // recogemos el token
        $token = $tokenResult->token;
        // creamos el response
        $response = [
            'user'          =>    User::find($this->id),
            'access_token'  =>    $tokenResult->accessToken,
            'token_type'    =>    'Bearer',

        ];
        return $response;

    }

    public function invitedToCurrent($request)
    {
      if($this->invited) {
        $this->fill($request->all());
        $this->password = bcrypt($request->password);
        $this->invited = false;
        $this->save();
      }
    }

    public function buyShoppingCart($request, &$purchase)
    {
      // solo si la hora del local se ecncuentra dentro
      if(Business::find(1)->today->count() == 0) {
        return 811;
      }

      // recogemos las ordenes y su precio. Hacemos el cargo de Stripe y si todo va bien entonces se añaden en un purchase
      // cogemos las ordenes seleccionadas.
      $orders = $this->shoppingCart()->pluck('id');
      if(auth()->user()->hasDefaultPaymentMethod()==false) {
        return 200;
      }
      $price = $this->shoppingCart->sum('price');
      // si es su cumpleaños le hacemos una ofert
      if(($price < env('MIN_BUY',5))) {
        return 205;
      }
      // oferta
      if(auth()->user()->birthday and Carbon::parse(auth()->user()->birthday)->isBirthday()) {
        $price = round($price*0.9,2);
      }
      // ahora ponemos en estado de loading
      $news = $this->shoppingCart()->update(['status' =>'loading']);
      $charge_id = null;
      // si llegamos hasta aqui es que todo ha ido bien,
      // asi que hemos de crear el purchase
      $purchase = new Purchase([
        "user_id"             => auth()->user()->id,
        "total_price"         => $price,
        "stripe_payment_id"   => $charge_id
      ]);
      // save the purchase
      $purchase->save();
      // cobramos
      if(!$purchase->CobrarCliente()) {
        // si el cobro sale mal devolvemos un error y eliminamos el purchae
        $purchase->delete();
        // devolvemos código de error
        return 201;
      }
      // now, update the orders to pending
      $this->orders()->whereIn('id',$orders)->update([
        'status'        => 'pending',
        'purchase_id'   => $purchase->id,
      ]);

      $purchase->mails();
      // paso de referencia
      $purchase = $purchase->id;
      return true;
    }

    public function openChatToBusinessFromPurchase($purchase_id)
    {
      if($purchase = Purchase::find($purchase_id)) {
          foreach ($purchase->orders as $order) {
            $order->product->business->giveMeOrCreateChatWith(auth()->user());
          }
      }
    }

    public static function generateUrlRoute($orders)
    {
      // $orders()->get();
      $user = auth()->user();
      $list = [];
      // los agrupamos por business
      // $orders = $orders->get();
      $orders = $orders->groupBy('product.business_id');
      //
      foreach ($orders as $id => $order) {
        if($business = Business::find($id)) {
          $list[] = $business;
        }
      }
      $base = "https://www.google.com/maps/dir/";
      $origen = $user->latitude.",".$user->longitude;
      foreach ($list as $business) {
        $origen.="/".$business->latitude.",".$business->longitude;
      }
      return ($base.$origen);
    }


    // create a token and send an email
    public function forgetPass()
    {
      // generamos token
      $token = md5(uniqid(rand(), true));
      // creamos correo
      $data = [
        "title"         => "Cambiar contraseña de ",
        "logoInTitle"   => true,
        "text"          => "Buenas $this->name, Si desea cambiar su contraseña haz click al enlace de abajo, de lo contrario ignorae éste mail",
        "option"        => [
          'text'  =>  "Cambiar Contraseña",
          'url'   =>  url('/password?token=').$token
        ]
      ];
      sendMail::dispatch(new BasicMail($data),$this->email);
      $this->remember_token = $token;
      $this->save();
    }

    public function reviews()
    {
       return $this->hasMany('App\Review');
    }

    public function scratches()
    {
       return $this->belongsToMany('App\Scratch','users_scratch')->withPivot(['used','available'])->orderBy('points','ASC');
    }


    public function delete()
    {
        $this->reviews()->delete();
        $this->business()->delete();
        $this->chats()->delete();
        $this->image()->delete();
        $this->notifications()->delete();
        $this->shoppingCart()->delete();
        $this->deleteClient();
        return parent::delete();
    }

    public  static function tabletate($data) {
      return [
        'headers' => [
          'identificador' =>  'id',
          'correo'  => 'email',
          'Nombre' =>  'name',
          'Associación' => [
            'model_name' => 'Association',
            'select'     => Association::all(), // data al seleccionar en crear
            'show'       => 'name',
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'remove'  => true,
          'edit' => true,
          'image'   => true,
        ],
        'singular' => 'user',
        'name'  => 'Usuarios',

      ];

    }




}