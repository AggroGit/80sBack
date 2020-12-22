<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\User;


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
      if(Carbon::parse($this->expires_at)->toDateString()>= Carbon::now()->toDateString()) {
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

    public  static function boot() {
        parent::boot();

        static::created(function($model) {
            $users = User::all();
            foreach ($users as $user) {
              $user->send([
                "title"   => 'Descuento disponible',
                "body"    => "¡Hay una oferta disponible del $model->percentage_dicount %, haz un pedido y aprovecha!",
                "sound"   => "default",
                "badge"   => 1,
                "type"    => "discount"
              ]);
            }
        });
    }

    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Título' =>  'title',
          'Subtítulo'  => 'subtitle',
          'Válido hasta'  => 'expires_at',
          '% de descuento'  => 'percentage_dicount',
        ],

        'data'  =>  $data,
        'options' => [
          'add'     => true,
          'remove'  => true,

        ],
        'singular' => 'discount',
        'name'  => 'Descuentos',

      ];

    }



}
