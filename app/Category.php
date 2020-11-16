<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Translation;

class Category extends Model
{
    //
    protected $with = ["image","category","translations"];



    protected $fillable = [
      'name','category_id'
    ];
    // image of the category
    public function image()
    {
        return $this->belongsTo('App\Image');
    }

    public function addTranslation($content,$lang = "cat")
    {
      $new            = new Translation();
      $new->language  = $lang;
      $new->content   = $content;
      $new->save();
      $this->translations()->save($new);
    }

    //
    public function translations()
    {
       return $this->hasMany('App\Translation');
    }

    // the superior category
    public function category()
    {
      return $this->belongsTo('App\Category')->orderBy('name');
    }

    public function subCategories()
    {
       return $this->hasMany('App\Category')->orderBy('name');//->has('business','>=',1);
    }

    public function subCategoriesBusiness()
    {
       return $this->hasMany('App\Category');//->has('business','>=',1);//->orHas('products','>=',1);
    }

    // products of the category
    public function products()
    {
       return $this->hasMany('App\Product');
    }

    public function business()
    {
        return $this->hasMany('App\Business');
    }

    public static function boot()
    {

      parent::boot();
      // self::saving(function($model){
      // });
      self::created(function($model){
        // create a translation
        $new = new Translation();
        $new->language = "es";
        $new->content = $model->name;
        $new->save();
        $model->translations()->save($new);
      });

    }

    public function getBusinessCountAttribute()
    {
      return $this->business()->count();
    }

    public function getProductsCountAttribute()
    {
      return $this->products()->where('hidden',false)->count();
    }

    public function getBusinessDistancedAttribute()
    {
      return $this->business()->distance(auth()->user()->latitude, auth()->user()->longitude)->get()->where('distance','<=',env('MAX_DISTANCE_FILTER',6))->count();
    }

    public function getProductsDistancedAttribute()
    {
      return $this->products()->where('hidden',false)->get()->where('business.distance','<=',env('MAX_DISTANCE_FILTER',6))->count();
    }

    public  static function tabletate($data=null) {
      return [
        'headers' => [
          'Nombre'         =>  'name',
          'categoría de' =>  [
            'model_name' => 'category',
            'select'     => Category::all(), // data al seleccionar en crear
            'show'       => 'name',
            'url'        => "admin/category/edit"
          ],
          'num Negocios'  => 'businessCount',
          'num Productos' => 'productsCount',
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'remove'  => true,
          'add'     => true,
          'image'   => true
        ],
        'singular' => 'category',
        'name'  => 'Categorías'
      ];

    }


}
