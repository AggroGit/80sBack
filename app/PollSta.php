<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PollSta extends Model
{
    //
    protected $table = 'poll_sta';

    protected $fillable = [
      'url_business','url_clients'
    ];
}
