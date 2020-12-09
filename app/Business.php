<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;
use App\Association;
use Carbon\Carbon;
use App\Schedule;
use App\Image;
use App\Order;
use App\Chat;
use App\User;

class Business extends Model
{
    use Geographical;



    protected $table = "business";
    protected $with = ["images","schedules"];
    protected $appends = ["today",'mediaScore','totalScores'];
    protected static $kilometers = true;

    protected $fillable = [
        'email', 'name', 'description', 'latitude','longitude', 'phone', 'direction', 'link'
    ];

    protected $casts = [
        'verified' => 'boolean',
    ];

    public function getNumProductsAttribute()
    {
      return $this->products->count();
    }

    public function getNumPedidosAttribute()
    {
      return Order::where('business_id',$this->id)->count();
    }

    // images of the business
    public function images()
    {
        return $this->belongsToMany('App\Image','business_images');
    }

    // sections of the business
    public function sections()
    {
      return $this->belongsToMany('App\Section','product_section','business_id','section_id')->distinct();
    }

    // messages of the chat
    public function chats()
    {
       return $this->hasMany('App\Chat')
                   ->orderBy('created_at','desc');
    }

    // products of the business
    public function products()
    {
      return $this->hasMany('App\Product')->where('hidden',false);
    }

    // reviews of the business
    public function reviews()
    {
      return $this->hasMany('App\Review');
    }

    public function allProducts()
    {
      return $this->hasMany('App\Product')->orderBy('updated_at','DESC');
    }

    // schedule of the business
    public function schedules()
    {
      return $this->hasMany('App\Schedule');
    }

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function association()
    {
      return $this->belongsTo('App\Association');
    }

    // category of the business
    public function category()
    {
      return $this->belongsTo('App\Category');
    }

    public function getMediaScoreAttribute()
    {
      return [
        'media' => round($this->reviews()->avg('score'),2)?? 0,
        'total' => $this->reviews()->count()
      ];

    }

    public function getTotalScoresAttribute()
    {
      return $this->reviews()->count();
    }

    public function discounts()
    {
       return $this->hasMany('App\Discount');
    }

    //check if the user can use this business as admin
    public function isAdmin(User $user)
    {
      return ($this->user_id == $user->id)? true:false;
    }

    public function getTodayAttribute()
    {
      return $this->schedules()->orderBy('open_from')
      ->where('day',$this->getEquivalentDay())
      ->whereTime('open_from','<=',now())
      ->whereTime('open_to','>=',now())
      ->get();

    }

    public function getEquivalentDay()
    {
      switch (Carbon::today()->format('l')) {
        case 'Monday':
        return "l";
          break;
        case 'Tuesday':
        return "m";
          break;
        case 'Wednesday':
        return "x";
          break;
        case 'Thursday':
        return "j";
          break;
          case 'Friday':
        return "v";
          break;
        case 'Saturday':
          return "s";
        break;
        case 'Sunday':
        return "d";
        break;
      }
    }

    public function canAcceptPerpetuaDiscounts()
    {
      return ($this->association and $this->association->searchName == "staPerpetua")? true:false;
    }

    public function createFromRequest($request)
    {
      // si tiene categoria y existe
      if($request->has('category_id') and !Category::find($request->category_id)) {
        return 1000;
      }
      $this->fill($request->all());
      // save
      $this->association_id = auth()->user()->association_id;
      $this->user_id = auth()->user()->id;
      $this->save();

      try {
        // check the images
        if($request->has('images')) {
          // delete
          $this->images()->delete();
          foreach ($request->images as $image) {
            // create and asociate the image
            $new = new Image();
            $new->create($image,"business");
            $new->save();
            $this->images()->save($new);
          }
        }
        $this->save();
      } catch (\Exception $e) {

      }


      // check the schedules
      if($request->has('schedule')) {
        // delete
        $this->schedules()->delete();
        foreach ($request->schedule as $schedulesIn) {
          $new = new Schedule($schedulesIn);
          $this->schedules()->save($new);
        }
      }
      if($this->association_id == Association::AsoPerpetua()->id) {
        // ahora le metemos el chat con el administrador
        if($admin = User::where('staPerpetuaAdmin',true)->first()) {
          $this->giveMeOrCreateChatWith($admin);
        }
      }





    }

    public function giveMeOrCreateChatWith(User $user)
    {
      // buscamos un chat del negocio que concuerde con el usuario entrado
      $chats = $this->chats;
      $chat = null;
      //
      foreach ($chats as $OurChat) {
        if($OurChat->isUser($user)) {
          $chat = $OurChat;
        }
      }
      // si no existe creamos uno
      if($chat == null) {
        $new = new Chat();
        $new->business_id = $this->id;
        // añadimos al usuario que queremos abrir
        $new->addUser($user);
        // añadimos al de la tienda
        $new->addUser($this->user);
        $new->save();
        return Chat::find($new->id);
      }
      return $chat;
    }

    public function sendChatHaveBuy($order)
    {

    }

    public function delete()
    {
        $this->products()->delete();
        $this->chats()->delete();
        $this->images()->delete();
        return parent::delete();
    }

    public  static function tabletate($data) {
      return [
        'headers' => [
          'Número de telefono' => 'phone',
          'Dirección' => 'direction',
          'Nombre' =>  'name',
          'Correo' =>  'email',
          'Productos' => 'numProducts',
          'Descripción' => 'description',
          'Pedidos'     => 'numPedidos',
          'Url' => 'link',
          'lat' => 'latitude',
          'long' => 'longitude'



        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => false,
          'image'   => false,
        ],
        'singular' => 'business',
        'name'  => 'Negocios',

      ];

    }









}
