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

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Día (l,m,x,j,v,s,d)'   =>  'day',
          'Desde las'             =>  'open_from',
          'Hasta las'             =>  'open_to',
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'add'     => true,

        ],
        'singular' => 'schedule',
        'name'  => 'Horario'
      ];

    }
}
