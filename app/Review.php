<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
      'text','score','business_id','user_id'
    ];
    protected $with=['user'];
    //
    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public function business()
    {
      return $this->belongsTo('App\Business');
    }

}
