<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Discount extends Model
{
    protected $fillable = [
      'title', 'subtitle', 'cost_points', 'percentage_dicount'
    ];
    //
    public function business()
    {
      return $this->belongsTo('App\Business');
    }

    // si su fecha es valida se usa, sino no
    public function validateDicount()
    {
      if(Carbon::parse($this->expires_at)<= Carbon::now()->toDateString()) {
        return true;
      }
      return false;
    }

    // public function addOrders($orders)
    // {
    //   foreach ($orders as $order) {
    //     $order->discount_id = $this->id;
    //     $order->price = round(
    //       $order->price - ($order->price*($this->percentage_dicount/100))
    //     ,2);
    //     $order->save();
    //   }
    //   auth()->user()->staPerpetuaPoints = auth()->user()->staPerpetuaPoints - $this->cost_points;
    //   auth()->user()->save();
    // }
    //
    // public function quitOrders($orders)
    // {
    //   if($orders->where('discount_id','!=',null)->count()>=1) {
    //     foreach ($orders as $order) {
    //       // reclalculamos el precio
    //       $order->calcPrice();
    //       // quitamos descuento
    //       $order->discount_id = null;
    //       $order->save();
    //     }
    //     auth()->user()->staPerpetuaPoints = auth()->user()->staPerpetuaPoints + $this->cost_points;
    //     auth()->user()->save();
    //   }
    //
    // }



}
