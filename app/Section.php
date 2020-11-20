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

    public function getProductsCountAttribute($value='')
    {
      // code...
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Nombre'         =>  'name',
          'num Productos' => 'productsCount',
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'add'     => true,
        ],
        'singular' => 'section',
        'name'  => 'Categorías'
      ];

    }




}
