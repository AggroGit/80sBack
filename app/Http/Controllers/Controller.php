<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Category;
use App\Business;
use App\Product;
use App\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Return a correct response
     *
     * @var Response
     */
    public function correct($response = null)
    {
        return response()->json([
            'rc'           => 1,
            'data'         => $response,
        ]);
    }

    /**
     * Return a incorrect response
     *
     * @var Response
     */
     public function incorrect($code = 0,$response = null, $extra = null)
     {
         $response = $response?? config('errors.'.$code);
         return response()->json([
             'rc'           => $code,
             'data'         => $response,
             'details'      => $extra
         ]);
     }

     /**
     *  Convierte un request con los datos en data en un tradicional request con los datos de 'data'
     *
     * @param  mixed
     * @return Request
     */
     public function data_to_request(Request $request)
     {
        if($request->has('data'))
        {
          $request = $request->input('data');
          return new Request(json_decode($request, true));
        } else {
          return $request;
        }
     }

     /**
      * Verifica el request con la config
      *
      * @return Error or Nothing
      */
     public function hasError($request, String $name)
     {
         // validamos el request con el nombre por indice del config
         $validator = Validator::make($request,config($name));

         // si la vañidación falla devolvemos los errores
         if ($validator->fails()){
            // var_dump($validator->errors());
           return $validator->errors();
         }
         // continue...
     }

     /**
      *  Return the Vue Front
      *
      * @return Blade->VueJS
      */
      public function vueFront()
      {
          return view('layouts.baseFront');
      }

      // a partir de una lista de comercios nos devuelve el mas lejano
      public function roundKm($list)
      {
        $km  = null;
        if(count($list) !== 0 ) {
           $km =  ceil(end($list)[0]->distance);
        }
        return $km;
      }

      // nos devuelve las categorias principales con sus subcategorias
      public function mainCategories()
      {
        return Category::with('subCategories')->orderBy('name')->where('category_id',null)->get();
      }

      // a partir de una categoría o subcategoría nos devuelva sus tiendas
      // hay que tener en cuenta que si es una subcategoria, entinces solo de
      // esa, pero si es una categoria entonces las tiendas con todas las subcategorias de estas.
      public function businessFromCategory($category_id)
      {
          $business = Business::distance(auth()->user()->latitude, auth()->user()->longitude)->with('category');
          if($category = Category::find($category_id)) {
              // vemos si se trata de categoria principal
              if($category->category_id == null) {
                // entonces es categoria principal
                // devolvemos las tiendas con esa categoria y sus subcategorias, si tiene
                $business = $business->whereIn('category_id', $category->subCategories()->pluck('id'))
                                     ->orWhere('category_id', $category_id);

              }
              else {
                // sino solo es una subcategoria, solo buscamos ese id
                $business = $business->whereHas('products',function ($products) use ($category){
                  $products->whereIn('category_id',$category->subCategories()->pluck('id'))
                          ->orWhere('category_id', $category->id);
                })->orWhere('category_id',$category_id);
                // ->whereIn('category_id', $category->subCategories()->pluck('id'))
                // ->orWhere('category_id', $category_id);
              }
            return $business;
          }
          return false;
      }

      // a partir de una categoría o subcategoría nos devuelva sus productos
      // hay que tener en cuenta que si es una subcategoria, entinces solo de
      // esa, pero si es una categoria entonces las tiendas con todas las subcategorias de estas.
      public function productsFromCategory($category_id)
      {
          $products = Product::distance(auth()->user()->latitude, auth()->user()->longitude)->with('category');
          if($category = Category::find($category_id)) {
              // vemos si se trata de categoria principal
              if($category->category_id == null) {
                // entonces es categoria principal
                // devolvemos las tiendas con esa categoria y sus subcategorias, si tiene
                $products = $products->whereIn('category_id',$category->subCategories()->pluck('id'))
                                     ->orWhere('category_id',$category_id);
              }
              else {
                // sino solo es una subcategoria, solo buscamos ese id
                $products = $products->where('category_id',$category_id);
              }
            return $products;
          }
          return false;
      }

      public function notFound($type)
      {
        switch ($type) {
          case 'value':
            // code...
            break;

          default:
            return response()->file('categories/c_ropa/50.png');
            break;
        }
      }

      public function testing()
      {
        return $this->correct(
          $this->generateUrlRoute(auth()->user()->shoppingCart()->get())
        );
      }



























}
