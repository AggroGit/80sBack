<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Business;
use App\Product;
use App\Order;
use Carbon\Carbon;
class IAController extends Controller
{
    //
    public function recomendations($business_id)
    {
      if($business = Business::find($business_id)) {
        // ordenes que gestionar hoy
        $pendings = Order::where([
          ['business_id',$business_id],
          ['status','pending']
          ])->count();
        // categoria mas exitosa
        $ordenes = Order::where('business_id',$business_id)->get()->groupBy('product.id');
        $first = null;
        $second = null;
        $count = 0;
        foreach ($ordenes as $key => $order) {
          if($product = Product::find($key)) {
            if($count < $order->count()){
              $second = $first;
              $first = $product;
            }
          }

        }
        return $this->correct([
          "orders_pendings" => $pendings,
          "better_product"  => $first->name?? false,
          "better_category" => $first->category->name?? false,
          "balance" => $this->statics($business_id),
          "we_recomend" => [
            $first,$second
          ],
          "cards" => [
            "ventas" => $this->cards($business)
          ],



        ]);
      }
      return $this->incorrect();
    }

    public function statics($business_id)
    {
      // contamos las ordenes de un periodo y lo comparamos con otro
      // de éste mes
      $current = Order::where([
        ['business_id',$business_id],
        ['created_at','<=',now()],
        ['created_at','>=',Carbon::now()->subDays(30)]
      ])->count();

      $last = Order::where([
        ['business_id',$business_id],
        ['created_at','<=',Carbon::now()->subDays(30)],
        ['created_at','>=',Carbon::now()->subDays(60)]
      ])->count();
      $balance = "negative";
      $percentage = 0;
      if($current > $last) {
        $balance = "positive";
        if ($last==0 or $current==0) {
          $percentage = 0;
        } else {
          $percentage = (($current/$last)*100)-100;
        }
      } else {
        if ($percentage!==0)
          $percentage = (($last/$current)*100)-100;
        else {
          $percentage = 0;
        }
      }
      return [
        "balance" => $balance,
        "percentage" => round($percentage,2)
      ];
    }

    // las tarjetas de datos, por ahora solo ventas
    public function cards($business)
    {
      $order =
      Order::where('business_id',$business->id)
        ->select('id', 'created_at')
        ->where('created_at','>=',Carbon::now()->subDays(7))
        ->orderBy('created_at','DESC')
        ->get()
        ->groupBy(function($date) {
          return Carbon::parse($date->created_at)->day; // grouping by day
          //return Carbon::parse($date->created_at)->format('m'); // grouping by months
      });
      $days = 7;
      $arr = $order->toArray();


      foreach ($arr as $key => $value) {
        $arr[$key] = count($value);
      }

      for ($i=0; $i < $days; $i++) {
        if(!isset($arr[Carbon::now()->subDays($i)->day])) {
          $arr[Carbon::now()->subDays($i)->day] = 0;
        }
      }

      return array_reverse($arr,true);

    }
}
