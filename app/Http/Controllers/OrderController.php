<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;

class OrderController extends Controller
{
    private $global;
    //
    public function __construct(Request $request)
    {   // check the order
        $this->middleware(['ChackOrder','isSelected']);
        $this->order = Order::find($request->order_id);

    }

    public function removeCart(int $order_id)
    {
      if($this->order->quantity>=2){
        $this->order->quitOne();
        $this->order->refresh();
        return $this->correct($this->order);
      } 
      return $this->incorrect();

    }

    public function removeAllCart(int $order_id)
    {
      $this->order->delete();
      return $this->correct();
    }

    public function addOne(int $order_id)
    {
      $this->order->addOne();
      $this->order->refresh();
      return $this->correct($this->order);
    }

    public function getById(int $order_id)
    {
      return $this->correct($this->order);
    }

    public function editCart(int $order_id,Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.editCart')) {
        return $this->incorrect(0,$missings);
      }
      // check if the size exists and is in the product
      if ($request->has('size_id') and !$this->order->product->sizes()->find($request->size_id)) {
        return $this->incorrect(901);
      }
      $this->order->fill($request->all());
      $this->order->calcPrice();
      $this->order->refresh();
      // si la orden tiene descuento se quita al editar
      if($this->order->discount) {
        $orders = Order::where('id',$this->order->id)->get();
        $this->order->discount->quitOrders($orders);
      }
      return $this->correct($this->order);
    }









}
