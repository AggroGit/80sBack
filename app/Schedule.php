<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Schedule extends Model
{
    //
    protected $table = "schedule";
    protected $fillable = [
      "name","open_to","open_from","day"
    ];
    public function getOpenFromAttribute($value)
    {
       return Carbon::parse($value)->format('H:i');
    }

    public function getOpenToAttribute($value)
    {
      return Carbon::parse($value)->format('H:i');
    }
}
