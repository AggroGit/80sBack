<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
      "name"
    ];
    // the products of the section
    public function products()
    {
      return $this->belongsToMany('App\Product','product_section','section_id','product_id')->where('hidden',false);
    }




}
