<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    //
    protected $table = "allergies";
    protected $with = "image";

    public function image()
    {
      return $this->belongsTo('App\Image');
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Nombre'         =>  'name',
        ],
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'add'     => true,
          'image'   => true
        ],
        'data'  =>  $data,
        'singular' => 'allergy',
        'name'  => 'Alergias'

      ];


    }

}
