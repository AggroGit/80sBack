<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Malhal\Geographical\Geographical;
use App\Category;
use App\Section;
use App\Image;
use App\Sizes;

class Product extends Model
{
    use Geographical;

    //
    protected static $kilometers = true;
    const LATITUDE  = 'lat';
    const LONGITUDE = 'long';

    protected $with = ["images","sizes","category","sections","allergies"];

    protected $appends = ["business_name"];

    protected $fillable = [
        'name', 'description', 'price_per', 'category_id', 'business_id','hidden','price','offer_price'
    ];

    public function orders()
    {
       return $this->hasMany('App\Order')
                   ->orderBy('created_at','desc');
    }

    public function images()
    {
      return $this->belongsToMany('App\Image','products_images');
    }

    // sizes of the product
    public function sizes()
    {
       return $this->hasMany('App\Sizes');
    }

    // business of the product
    public function business()
    {
      return $this->belongsTo('App\Business')->distance(auth()->user()->latitude, auth()->user()->longitude);
    }

    // business of the product with the coordenates
    public function businessInRadius()
    {
      return $this->business()->geofence(19,42,9,9);
    }

    // category of the product
    public function category()
    {
      return $this->belongsTo('App\Category');
    }

    //
    public function cheapestSize()
    {
      $this->sizes()->orderBy('price','DESC')->first();
    }

    // secciones a las que pertenece el producto (etiquetas?)
    public function sections()
    {
      return $this->belongsToMany('App\Section');
    }

    // alergias que tiene el producto
    public function allergies()
    {
      return $this->belongsToMany('App\Allergy','product_allergy', 'product_id', 'allergy_id');
    }

    public function getBusinessNameAttribute()
    {
      return $this->business->name?? 'n';
    }

    public function businessDistanced()
    {
      return $this->business()->distance(auth()->user()->latitude, auth()->user()->longitude);
    }

    // a partir de un nombre de sección creamos o asignamos un producto a una o vsrias secciones
    public function addSectionByName($name)
    {
      // si no hay una con su nombre creamos una
      if(!$section = Section::where('name',$name)->first()) {
        // sino creamos una sección
        $section = new Section([
          "name"        => $name,
        ]);
        $section->save();
      }
      $this->sections()->save($section,['business_id' => $this->business_id]);
    }

    public function addSubcategoryByName($name)
    {
      if($cat = Category::where([
        "name"  => $name
        ])->first()) {
        $this->category_id = $cat->id;
        $this->save();
      }
    }



    // unify the creation and the edit of a product from the request
    public function createFromRequest($request,&$error = false)
    {
      // lo primero de todo lo que se coge se cambia
      $this->fill($request->all());
      if($request->has('category_id')) {
        // si no existe la cat
        if(!Category::find($request->category_id)) {
          $error = 1000;
          return false;
        }
      }

      else {
        $this->category_id = auth()->user()->business->category_id;
      }

      // ahora asociamos el business id
      $this->business_id = $request->business_id;
      $this->save();
      // ahora la parte de las imagenes
      if($request->has('images')) {
        // recorremos
        foreach ($request->images as $file) {
            $image = new Image();
            $image->create($file,'products');
            $this->images()->save($image);
        }
      }

      // si tiene seccion se habría de añadir
      if($request->has('sections')) {
        $this->sections()->sync([]);
        $this->save();
        //
        foreach ($request->sections as $section) {
          // creamos y añadimos
          $this->addSectionByName($section['name']);
          $this->addSubcategoryByName($section['name']);

        }
      }
      $this->save();
      // si tiene sizes replicamos producto con su size

      if($request->has('sizes')) {
        foreach ($request->sizes as $size) {
          // duplicamo
          $new = $this->replicate();
          // $new->business_id = $request->business_id;
          $new->fill($size);
          $new->save();
          // copiamos secciones
          foreach ($this->sections as $section) {
              $new->addSectionByName($section['name']);
              $new->save();
          }
          //
          if($request->has('images')) {
            // recorremos
            foreach ($this->images as $image) {
              $new->images()->save($image->duplicateImage());
            }
          }

          $new->save();


        }

      }




    }

    public function delete()
    {
        $this->orders()->delete();
        $this->images()->delete();
        $this->sections()->sync([]);
        return parent::delete();
    }


    public  static function tabletate($data = null) {
      return [
        'headers' => [
          'Nombre' =>  'name',

          'Categoría' => [
            'model_name' => 'section',
            'select'     => Section::all(), // data al seleccionar en crear
            'show'       => 'name',
            'url'        => "admin/section/edit"
          ],
        ],
        'data'  =>  $data,
        'options' => [
          'edit'    => true,
          'add'     => true,
          'remove'  => true,
          'image'   => true,
          'images'  => true,
        ],
        'singular' => 'product',
        'name'  => 'Productos',

      ];

    }
}
