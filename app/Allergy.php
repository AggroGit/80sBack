<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    //
    protected $table = "allergies";
    protected $with = "image";

    // the image of the message
    public function image()
    {
      return $this->belongsTo('App\Image');
    }

}
