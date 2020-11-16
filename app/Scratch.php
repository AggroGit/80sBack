<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scratch extends Model
{
    //

    protected $fillable = [
      'points'
    ];

    protected $casts = [
        'hidden' => 'boolean',
    ];

    protected $table = 'scratch';

    public function users()
    {
       return $this->hasMany('App\User');
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Puntos'         =>  'points',
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'add'     => true,
        ],
        'singular' => 'scratch',
        'name'  => 'Rasca i guanya'
      ];

    }
}
