<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayOut extends Model
{
    //
    protected $table = 'pay_outs';

    protected $fillable = [
      'price_sended', 'comision', 'user_id', 'purchase_id', 'business_id', 'money_send_at'
    ];

    public function user()
    {
      return $this->belongsTo('App\User');
    }
}
