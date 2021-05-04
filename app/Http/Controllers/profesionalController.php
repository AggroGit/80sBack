<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business;
use App\Product;
use App\Sizes;
use App\Image;
use App\Order;
use App\Purchase;

class profesionalController extends Controller
{

    public function business($business_id)
    {
       return $this->correct(Business::with('allProducts')->find($business_id));
    }

    public function removeBusiness($id)
    {
      if(!$business = Business::find($id)) {
          return redirect('/');
      }
      foreach ($business->images as $image) {
        $image->destroyImage();
      }
      foreach ($business->products as $product) {
        foreach ($product->images as $image) {
          $image->destroyImage();
        }
        $product->delete();
      }
    }

    public function index()
    {
      // code...
      return $this->correct(Business::with('allProducts')->find(auth()->user()->business->id));
    }

    public function products($business_id, Request $request)
    {
      // check
      if ($missings = $this->hasError($request->all(),'validation.getProducts')) {
        return $this->incorrect(0,$missings);
      }
      $products = Business::find($business_id)->allProducts();
      //
      if($request->has('search')) {
        $products = $products->where('name','like', "%$request->search%");
      }
      //
      if($request->has('invisibles') and $request->invisibles == true)
      $products = $products->where('hidden',false);
      //
      if($request->has('minPrice'))
      $products = $products->where('price', '>=', $request->minPrice);
      //
      if($request->has('maxPrice'))
      $products = $products->where('price', '<=', $request->maxPrice);

      // if(!$request->importantFirst)
      $products = $products->orderBy('sales','desc');

      return $this->correct($products->get());
    }

    //
    public function createProduct($business_id, Request $request)
    {
      // check
      if ($missings = $this->hasError($request->all(),'validation.createProduct')) {
        return $this->incorrect(0,$missings);
      }
      // create the product
      $product = new Product();
      $error = false;
      $product->createFromRequest($request,$error);
      if($error !== false) {
        return $this->incorrect($error);
      }
      //
      return $this->correct(Product::find($product->id));

    }

    public function editProduct($business_id, $product_id, Request $request)
    {
        if ($missings = $this->hasError($request->all(),'validation.editProduct')) {
          return $this->incorrect(0,$missings);
        }
        if(!$product = Product::find($product_id)) {
            return $this->incorrect(900);
        }
        if ($product->business_id != $business_id) {
          return $this->incorrect(4);
        }
        $error = false;
        $product->createFromRequest($request,$error);
        if($error !== false) {
          return $this->incorrect($error);
        }
        $product->save();
        $product->refresh();
        return $this->correct($product);
    }

    public function createBusiness(Request $request)
    {

      //
      if ($missings = $this->hasError($request->all(),'validation.createBusiness')) {
        return $this->incorrect(0,$missings);
      }
      //
      if(auth()->user()->business) {
        return $this->incorrect(4);
      }
      // create the business
      $business = new Business();
      if($error = $business->createFromRequest($request) and $error !== true) {
        return $this->incorrect($error);
      }

      return $this->correct(Business::find($business->id));

    }

    public function editBusiness($business_id, Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.editBusiness')) {
        return $this->incorrect(0,$missings);
      }
      $business = Business::find($business_id);
      // aplica los cambios
      $business->createFromRequest($request);
      $business->refresh();
      return $this->correct($business);
    }

    // add image to the business
    public function addImage($business_id, Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addImage')) {
        return $this->incorrect(0,$missings);
      }
      $business = Business::find($business_id);
      //
      $new = new Image();
      $new->create($request->image,"business");
      $new->save();
      $business->images()->save($new);
      $new->refresh();
      return $this->correct($new);
    }

    public function history($business_id)
    {

      $purchases_exists = Purchase::all()->pluck('id');
      $orders_pendings = Order::with('purchase')->where([
        ['business_id',$business_id],
        ['status','pending'],
      ])->whereIn('purchase_id',$purchases_exists)->get()->groupBy('purchase_id')->toArray();
      $pendings = [];
      foreach ($orders_pendings as $purchase_id => $orders) {
        if($purchase = Purchase::with('user')->find($purchase_id)->toArray()) {
          $purchase['orders'] = $orders;
          $pendings[] = $purchase;
        }
      }



      $orders_finished = Order::with('purchase')->where([
        ['business_id',$business_id],
        ['status','!=','pending'],
      ])->get()->groupBy('purchase_id')->toArray();
      $finished = [];
      foreach ($orders_finished as $purchase_id => $orders) {
        if($purchase_original = Purchase::with('user')->find($purchase_id)) {
          $purchase = $purchase_original->toArray();
          $purchase['orders'] = $orders;
          $purchase['payment'] = $purchase_original->payOuts()->where('business_id',$business_id)->first();
          $finished[] = $purchase;
        }
      }

      $this->calcPriceOfBusiness($pendings);
      $this->calcPriceOfBusiness($finished);



      // var_dump($orders_pendings);
      return $this->correct([
        "pending"   => $pendings,
        "finished"  => $finished

      ]);






      // hemos de devolver los pendientes y los finalizados
      // cogemos las ordenes que sean de la tienda y los agrupamos poor usuario
      $pending = Purchase::whereHas('orders',function ($orders) use($business_id){
        $orders->where([
          ['status','pending'],
          ['business_id', $business_id]
        ]);
      })->with('user');//->where('order.business_id',$business_id);
      //
      $finished = Purchase::whereHas('orders',function ($orders) use($business_id){
        $orders->where([
          ['status','!=','pending'],
          ['business_id',$business_id]
        ]);
      })->with('user');
      //
      $pending  = $pending->get();
      $finished   = $finished->get();
      // ponemos el payment, si es hay
      foreach ($finished as $finish) {
        if($pay = $finish->payOuts()->where('business_id',$business_id)->first()) {
            $finish->payment = $pay;
        } else {
          $finish->payment = null;
        }
      }
      //
      $this->calcPriceOfBusiness($pending,$business_id);
      $this->calcPriceOfBusiness($finished,$business_id);
      //
      return $this->correct([
        "pending"   => $pending,
        "finished"  => $finished
      ]);
    }


    public function calcPriceOfBusiness(&$purchases)
    {

      foreach ($purchases as $key=>$purchase) {
        $p = 0;
        foreach ($purchase['orders'] as $order) {
          $p = $p+$order['price'];
        }
        $purchases[$key]['total_price_for_business'] = $p;
      }
    }

    public function cancelOrder($business_id, $purchase_id, Request $request)
    {

      if(!$purchase=Purchase::find($purchase_id)) {
        return $this->incorrect(1105);
      }
      // hemos de coger las ordenes de la tienda donde sea del usuario que nos pasa por la request
      $business = Business::find($business_id);
      $purchase = Purchase::find($purchase_id);
      // return $this->correct($business->orders);
      //
      $orders = Order::where([
        ['purchase_id',$purchase_id],
        ['status',     Order::statusToBeCancel()],
        ['business_id', $business_id]
      ]);
      if($orders->count()<1){
        return $this->incorrect(1106);
      }
      //
      $old = $orders->get();

      $update = $orders->update([
        "status" => "canceled"
      ]);
      //
      $purchase->recalculateTotalPrice();
      $purchase->notifyCancel($business);
      return $this->correct();

    }

    // a partir de unas ordenes les devolvemos su dinero
    public function returnOrdersMoney($purchase,$orders)
    {
      $r = $purchase->user->refund($purchase->stripe_payment_id,[
        "amount" => $orders->sum('price')*100,
      ]);
      //
      $orders->update([
        "stripe_refound_id" => $r->id
      ]);
    }

    public function deliverOrder($business_id, $purchase_id, Request $request)
    {

      // hemos de coger las ordenes de la tienda donde sea del usuario que nos pasa por la request
      $business = Business::find($business_id);
      $purchase = Purchase::find($purchase_id);
      if(!$purchase) {
        return $this->incorrect(1105);
      }
      if($purchase->completed) {
        return $this->incorrect(1301);
      }

      $orders = Order::where([
        ['purchase_id', $purchase_id],
        ['status',      'pending'],
        ['business_id', $business_id]
      ]);
      //
      if($orders->count() == 0) {
        return $this->incorrect(1301);
      }

      $orders->update([
        "status" => "completed"
      ]);
      return $this->correct();
      // return $this->correct($orders->get());
    }

    public function addingImageProduct($business_id, $product_id, Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addImage')) {
        return $this->incorrect(0,$missings);
      }
      if ($product = Product::find($product_id)) {
        $new = new Image();
        $new->create($request->image,"products");
        $new->save();
        $product->images()->save($new);
        $new->refresh();
        return $this->correct($new);

      } else {
        return $this->incorrect(901);

      }
    }


    public function iA()
    {
      // code...
    }

    public function removeProducts(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.removeProducts')) {
        return $this->incorrect(0,$missings);
      }
      foreach ($request->ids as $id) {
        if($p = Product::find($id)) {
          $p->delete();
        }
      }
      return $this->correct();
    }





}
