<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Association;
use App\Business;
use App\Discount;
use App\Scratch;
use App\StaNoti;
use App\PollSta;
use App\Review;
use App\Order;
use App\Image;
use App\News;
use App\User;





class staPerpetuaController extends Controller
{
    public function AsoPerpetua()
    {
      return Association::where('searchName','staPerpetua')->first();
    }
    // check idf the asso id is staPerpetua
    public function isStaPerpetua($id) {
      if($aso = Association::find($id) and $aso->searchName == "staPerpetua") {
        return $aso;
      }
      return false;
    }

    // list discounts
    public function getDiscountsByBusiness($id)
    {
      if($business = Business::find($id) and $aso = $this->isStaPerpetua($business->association_id)) {
        return $this->correct($business->discounts);
      }
      return $this->incorrect(804);
    }

    // cogemos las órdenes del usuario logeado, donde el business sea el mismo que el discount y ademas sean de sta perpetua. Luego aplicar en las ordenes el discount
    public function applyDiscount(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.applyDiscount')) {
        return $this->incorrect(0,$missings);
      }
      foreach ($request->ids as $discount_id) {
        if($discount = Discount::find($discount_id)) {
          // vemos que la tienda es de staPerpetua
          if($discount->business->canAcceptPerpetuaDiscounts()) {
            // cogemos las ordenes del usuario que tengan esta tienda
            $orders = auth()->user()->shoppingCart()->where('business_id',$discount->business->id)->get();
            // les quitamos el descuento porque ya les aplicamos uno
            $discount->quitOrders($orders);
            //
            if(auth()->user()->staPerpetuaPoints < $discount->cost_points) {
              return $this->incorrect(1402);
            }
            // ahora lo ponemos
            $discount->addOrders($orders);
          }
        }
      }
      //
      return $this->correct();
    }























    public function calcPointsCostOrders($orders)
    {
      $p = 0;
      foreach ($orders as $order) {
        if($order->discount) {
          $p= $p + $order->discount->cost_points;
        }
        // code...
      }
      return $p;
    }

    // de las ordenes que recibimos, de las que son de sta perpetua, deberemos aplicar descuentos
    public function buyInStaPerpetua(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.buy')) {
        return $this->incorrect(0,$missings);
      }
      $purchase = null;
      // ahora la parte que le correspondería al user la ponemos aqui
      if($request->pay_in_hand == false and auth()->user()->hasDefaultPaymentMethod()==false) {
        return $this->incorrect(200);
      }
      //


    }



    public function listDiscounts()
    {
      // a partir del carrito agrupamos por business que tienen para ofrecer descuentos
      $business = auth()->user()->shoppingCart()->get()->where('business.association_id',$this->AsoPerpetua()->id)->groupBy('business.id');
      $ids = [];
      foreach ($business as $key => $business) {
        $ids [] = $key;
      }
      // $discounts = Discount::whereIn('business_id',$ids)->get()->groupBy('business.name')
      $b = Business::whereIn('id',$ids)->with('discounts')->get();
      return $this->correct($b);
    }

    public function shoppingCartSta()
    {
      $return = auth()->user()->completeShoppingCart();
      // $this->calcOriginalPrice()->toArray();
      $return = array_merge($return,[
        'available_discounts' => Discount::whereIn('business_id',
            auth()->user()->shoppingCart()->pluck('business_id')
        )->count(),
      ]);
      $return = array_merge($return,[
        'original_orders' => $this->calcOriginalPrice()

      ]);

      $return = array_merge($return,[
        'total_no_discounts' => round($this->calcOriginalPrice()->sum('price'),2)
      ]);

      $return = array_merge($return,[
        'pointsCost' => $this->calcPointsCostTotal()
      ]);


      return $this->correct($return);

    }

    public function calcOriginalPrice()
    {
      $orders = auth()->user()->shoppingCart;
      foreach ($orders as $order) {
        $order->quantity = $order->quantity?? 1;
        $order->price = round($order->getTotalPriceByOne() * $order->quantity,2);
      }
      return $orders;
    }

    // suma los puntos que ha costado usar el decuento y los resta del usuario
    public function canBuyByPoints($orders)
    {
      $cost = $this->calcPointsCostOrders($orders);
      echo "coste $cost";
      echo 'tenemos->'. auth()->user()->staPerpetuaPoints;
      if($cost > auth()->user()->staPerpetuaPoints) {
        echo "NO PUEDE";
        return false;
      }
      auth()->user()->staPerpetuaPoints = auth()->user()->staPerpetuaPoints - $cost;
      auth()->user()->save();
      return true;
    }

    public function checkAnyHas($orders)
    {
      foreach ($orders as $order) {
        if($order->discount){
          return true;
        }
      }
      return false;
    }

    public function calcPointsCostTotal()
    {

      $p = 0;
      $orders = auth()->user()->shoppingCart()->get()->groupBy('discount_id');
      foreach ($orders as $key => $order) {
        if($d = Discount::find($key)) {
          $p = $p + $d->cost_points;
        }
      }
      return $p;
    }

    public function listDiscountsByBusiness($business_id)
    {
      if($business = Business::find($business_id)) {
        return $this->correct($business->discounts);
      }
      return $this->incorrect();
    }

    public function addReview($business_id, Request $request)
    {
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.addReview')) {
        return $this->incorrect(0,$missings);
      }
      if(!Business::find($business_id)) {
        return $this->incorrect(804);
      }
      if(auth()->user()->reviews()->where('business_id',$business_id,)->first()) {
        return $this->incorrect(810);
      }
      $review = new Review($request->all());
      $review->user_id = auth()->user()->id;
      $review->business_id = $business_id;
      $review->save();
      return $this->correct($review);
    }

    public function reviewsList($business_id)
    {
      if(!$business = Business::with('reviews')->find($business_id)) {
        return $this->incorrect(804);
      }
      return $this->correct($business);
    }

    public function listScratch()
    {
      // listamos los scratches que no ha racsado
      return $this->correct(auth()->user()->scratches);
    }

    public function quiz()
    {
      if($p = PollSta::all()->first()) {
        // dependiendo del tipo de usuario
        $url = (auth()->user()->type == "business")? $p->url_business:$p->url_clients;
        return $this->correct($url);
      }

      $url = (auth()->user()->type == "business")? "https://merco.app/":"https://merco.app/";
      return $this->correct($url);

    }

    public function applyScratch($id)
    {
      if($s = Scratch::find($id)) {
        return auth()->user()->useRascaYGuanya($id)?
        $this->correct($s->points) : $this->incorrect(1403);
      }
      return $this->incorrect(1400);
    }

    public function addDiscount($business_id, Request $request)
    {
      if(!$business = Business::find($business_id)) {
        return $this->incorrect(804);
      }
      if($business->user->id !== auth()->user()->id) {
        return $this->incorrect(4);
      }
      if ($missings = $this->hasError($request->all(),'validation.addDiscount')) {
        return $this->incorrect(0,$missings);
      }
      $discount = new Discount($request->all());
      $discount->business_id = $business_id;
      $discount->save();
      return $this->correct();
    }

    public function dashboard()
    {
      return view('perpetua.layouts.dashboard');
    }

    public function news()
    {
      $tabletate = News::tabletate(News::where('association_id',$this->AsoPerpetua()->id)->get());
      //
      $tabletate['headers']['Associación'] = null;
      //
      return view('perpetua.layouts.tableList')->with([
        'tabletate' => $tabletate,
        'noTypeScript'  => true
      ]);
    }

    public function staNotis()
    {
      $tabletate = StaNoti::tabletate(StaNoti::where('sended',false)->get());
      //
      return view('perpetua.layouts.tableList')->with([
        'tabletate' => $tabletate,
        'noTypeScript'  => true
      ]);
    }

    public function staRasca()
    {
      $tabletate = Scratch::tabletate(Scratch::all());
      //
      return view('perpetua.layouts.tableList')->with([
        'tabletate' => $tabletate,
        'noTypeScript'  => true
      ]);
    }

    public function editRascaView($id = false)
    {

      if($id and $data = Scratch::find($id)) {
        return view('perpetua.layouts.editRasca')->with('data',$data);
      } else if(!$id){
        return view('perpetua.layouts.editRasca');
      }
      return back();
    }


    public function getModel($name)
    {
      // code...
      $className = 'App\\'.ucwords($name);

      if(class_exists($className)) {
          $model = new $className;
          return $model;
      }
      return false;
    }

    public function addNewView($id = false)
    {
      if($id and $data = News::find($id)) {
        return view('perpetua.layouts.addNew')->with('data',$data);
      } else if(!$id){
        return view('perpetua.layouts.addNew');
      }
      return back();

    }

    public function addNew(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addNew')) {
        return back();
      }
        if(!$new = News::find($request->id)) {
          $new = new News();
        }
        if($request->has('image')) {
          $image = new Image();
          $image->create($request->image);
          $new->image_id = $image->id;
        }
        $new->fill($request->all());
        $new->association_id = $this->AsoPerpetua()->id;
        $new->save();
        return redirect('perpetua/admin/news');

    }

    public function notifications()
    {
      return view('perpetua.layouts.notifications');
    }

    public function addNotiView($id = false)
    {
      if($id and $data = StaNoti::find($id)) {
        return view('perpetua.layouts.addNoti')->with('data',$data);
      } else if(!$id){
        return view('perpetua.layouts.addNoti');
      }
      return back();

    }

    public function encuestas($id = false)
    {
      if($data = PollSta::all()->first()) {
        return view('perpetua.layouts.addEncuesta')->with('data',$data);
      } else if(!$id) {
        return view('perpetua.layouts.addEncuesta');
      }
      return back();

    }

    public function users($id = false)
    {
      $tabletate = User::tabletate(User::where('association_id',$this->AsoPerpetua()->id)->get());
      //
      return view('perpetua.layouts.tableList')->with([
        'tabletate' => $tabletate,
        'noTypeScript'  => true
      ]);

    }

    public function addEncuestas(Request $request)
    {
       if($request->has('id') and $encuesta = PollSta::find($request->id)) {

       } else {
         $encuesta = new PollSta();
       }
       $encuesta->fill($request->all());
       $encuesta->save();
       return redirect('perpetua/admin/encuestas');
    }

    public function addNoti(Request $request)
    {
      if($request->has('id') and $encuesta = StaNoti::find($request->id)) {

      } else {
        $encuesta = new StaNoti();
      }
      $encuesta->fill($request->all());
      $encuesta->save();
      return redirect('perpetua/admin/notifications');
    }

    public function removeNoti($id)
    {
      if($noti = StaNoti::find($id)) {
        $noti->delete();
        return redirect('perpetua/admin/notifications');
      }
    }

    public function removeNew($id)
    {
      if($noti = News::find($id)) {
        $noti->delete();
        return redirect('perpetua/admin/news');
      }
    }

    public function removeRasca($id)
    {
      if($noti = Scratch::find($id)) {
        $noti->delete();
        return redirect('perpetua/admin/scratch');
      }
    }

    public function addOrEditRasca(Request $request)
    {
      if($request->has('id') and $encuesta = Scratch::find($request->id)) {

      } else {
        $encuesta = new Scratch();
      }
      $encuesta->fill($request->all());
      $encuesta->save();
      return redirect('perpetua/admin/scratch');
    }

    public function addUserView($id = false)
    {
      if($id and $data = News::find($id)) {
        return view('perpetua.layouts.addNew')->with('data',$data);
      } else if(!$id){
        return view('perpetua.layouts.addNew');
      }
      return back();

    }

    public function addOrEditUser(Request $request)
    {
      // code...
    }







}
