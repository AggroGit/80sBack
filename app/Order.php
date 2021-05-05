<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Discount;
use App\Sizes;
use App\Order;

class Order extends Model
{
    //
    protected $with = ['size', 'product','discount'];

    protected $fillable = [
      'quantity', 'price', 'size_id', 'description', 'howmuch'
    ];

    protected $casts = [
        'pay_in_hand'           => 'boolean'
    ];

    public function purchase()
    {
      return $this->belongsTo('App\Purchase')->without('orders')->with('user');
    }

    public function business()
    {
      return $this->belongsTo('App\Business');
    }


    public static function statusToBeCancel()
    {
      return [
        'pending'
      ];
    }

    public static function statusFinished()
    {
      return [
        'canceled','failed','completed','finished'
      ];
    }

    public static function statusToBeDelivered($value='')
    {
      return [
        'pending'
      ];
    }

    public function canBeDelivered()
    {
      return ($this->status == "pending");
    }


    // the product o f the order
    public function product()
    {
      return $this->belongsTo('App\Product');
    }

    public function size()
    {
      return $this->belongsTo('App\Sizes');
    }

    // the user of the order
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    // the discount of the order
    public function discount()
    {
      return $this->belongsTo('App\Discount');
    }

    // from a quantity and the sizes method, get the price
    public function getPrice()
    {
      $price = false;
      // if the size is null, get the price of the product or of the size
      if($this->size == null) {
        $price = $this->product->offer_price?? $this->product->price;
      } else {
        // if not, get the price of the size
        $price = $this->size->offer_price?? $this->size->price;
      }
      return $price;

    }

    // from the quantities and the price. Get the price
    public function getTotalPriceByOne()
    {
      // si la unidsd es en algo tnagible de peso o volumen, se calculará con el howmuch,
      // sino por cantidades
      // if($this->price_per == "unit" or $this->price_per == "pack_of_units") {
      //   return $this->getPrice();
      // }
      return $this->getPrice()*$this->howmuch;
    }

    public function calcPrice()
    {
      $this->quantity = $this->quantity?? 1;
      $this->price = round($this->getTotalPriceByOne() * $this->quantity,2);
      $this->save();
    }


    // from the request we have to create the order
    public function createFromRequest($request)
    {
      $this->product_id = $request->product_id;
      // now fill
      $this->fill($request->all());
      //
      $this->description = $this->remove_emoji($request->description);
      //
      $this->user_id = auth()->user()->id;
      $this->price_per = $this->product->price_per;
      $this->is_offer = $this->isAnOffer();
      $this->calcPrice(); // it save

    }

    function remove_emoji($string) {

        // Match Emoticons
        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        // Match Miscellaneous Symbols and Pictographs
        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        // Match Transport And Map Symbols
        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        // Match Miscellaneous Symbols
        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        // Match Dingbats
        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }

    // return if the price is an offer
    public function isAnOffer()
    {
      // si tiene size estonces lo vemos del size. Sino del product
      if($size = $this->size) {
        return ($size->offer_price !== null);
      }
      // sino devolvemos
      return ($this->product->offer_price !== null);
    }

    // add OneQuantity
    public function addOne()
    {

      if($this->discount) {
        $orders = Order::where('id',$this->id)->get();
        $this->discount->quitOrders($orders);
      }
      $this->quantity++;
      $this->calcPrice();
      $this->save();
      return $this->save();
    }

    public function quitOne()
    {
      if($this->discount) {
        $orders = Order::where('id',$this->id)->get();
        $this->discount->quitOrders($orders);
      }
      if($this->quantity >= 2) {
        $this->quantity--;
        $this->calcPrice();
        return $this->save();
      }

      return false;
    }


}
