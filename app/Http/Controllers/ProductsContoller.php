<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business;
use App\Section;
use App\Product;
use App\Order;
use App\Chat;

class ProductsContoller extends Controller
{
    //
    public function business($business_id,Request $request)
    {
      if($business = Business::distance(auth()->user()->latitude, auth()->user()->longitude)->find($business_id)) {
        $sections = $business->sections->toArray();
        foreach ($sections as $key => $section) {
          $sections[$key]['products'] = Section::find($sections[$key]['id'])->products()->where('products.business_id',$business_id)->get();
        }
        return $this->correct([
          "info"            => $business,
          "all_products"    => $business->products()->get(),
          "sections"        => $sections
        ]);
      }
      // no existe la business
      return $this->incorrect(804);
    }

    // añade un producto a la cesta
    public function addToCart(Request $request, $product_id)
    {
      // check
      if ($missings = $this->hasError($request->all(),'validation.addToCart')) {
        return $this->incorrect(0,$missings);
      }
      // product not found
      if(!$product = Product::find($product_id)) {
        return $this->incorrect(900);
      }
      // check if the size exists and is in the product
      if ($request->has('size_id') and !$product->sizes()->find($request->size_id)) {
        return $this->incorrect(901);
      }
      // now we have to create the order
      $order = new Order();
      if ($error = $order->createFromRequest($request) and $error !== true) {
        return $this->incorrect($error);
      }
      $order->business_id = $product->business_id;
      $order->save();
      return $this->correct(Order::find($order->id));

    }


    public function getById($product_id)
    {
      if($p = Product::find($product_id)) {
        $p->views++;
        $p->save();
        return $this->correct($p);
      }
      return $this->incorrect(900);
    }

    // nos abre chat con la tienda
    public function BusinessChat($business_id)
    {
      if($business = Business::find($business_id)) {
        $chat = $business->giveMeOrCreateChatWith(auth()->user());
        return $this->correct($chat);
      }
      return $this->incorrect(804);

    }

    //
    public function getSections($business_id)
    {
      if($business = Business::find($business_id)) {
        return $this->correct($business->sections);
      }
    }

}
