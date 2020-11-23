<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Events\StripeEvent;
use App\Events\MessageEvent;
use App\Purchase;
use App\Discount;
use App\Category;
use App\Business;
use App\Product;
use App\Section;
use App\Message;
use App\Image;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function start()
    {
      return view('welcome');
    }

    // menu de alimentacion, bares u otros
    public function menu($cat, $subcat = null)
    {
      // verificamos que exista categoria
      if ($category = Category::find($cat)) {
        // cogemos los negocios de la redonda
        $business = Business::distance(auth()->user()->latitude, auth()->user()->longitude,2);
        $businessNear = $business->get()->where('distance','<=',6);
        // damos buen formato
        $busDef=[];
        foreach ($businessNear as $key => $value) {
          $busDef[]=$value;
        }
        // ya lo tenemos
        $busDef = collect($busDef);
        // ahora los productos de éstos negocios
        $products = Product::whereIn('business_id',$busDef->pluck('id'));
        // cogemos las subcategorias a partir de la categoria y que algun producto la contenga
        $category->sub_categories = $category->subCategories()
                                             ->whereIn('id',$products->pluck('category_id'))
                                             ->get();
        // si es subcategoria deberiamos verificar que es realmente subcat de la cat
        if ($subcat !== null) {
          // vemos si existe
          if (!$subcategory = $category->subCategories()->find($subcat)) {
            // no existe subcategoria
            return $this->incorrect(1001,null,"existe categoria $category->name pero no ésta subcategoria con id $subcat en ella");
          }
        }
        // en este punto ya tenemos la categoria y la subcategoria :D
        $cat = $subcat?? $cat;
        //
        $popular = $this->businessFromCategory($cat)->orderBy('distance','ASC');//->wherein('category_id',$busDef->pluck('id'))->get();//->where('distance','<=',env('MAX_DISTANCE_FILTER',6));
        // si es categoria principal devolvemos todos
        if($subcat == null) {
            $popular=$popular->get()->where('distance','<=',env('MAX_DISTANCE_FILTER',6));
        } else {
          // sino, lo mism
          $popular=$popular->get()->where('distance','<=',env('MAX_DISTANCE_FILTER',6));
        }
        // distancia
        $km = $this->roundKm($popular)?? 0 ;
        // listado de los mas cercanos
        $near = $this->businessFromCategory($cat)->orderBy('distance','ASC')->paginate(50);
        //
        $real = $near->where('distance','<=',env('MAX_DISTANCE_FILTER',6));
        $near = $near->toArray();
        $near['data'] = $real;
        // RETURN
        return $this->correct([
          "populars" => [
            "km" => $km,
            "business" => $popular
          ],

          "near" => $near,//->where('distance','<=',env('MAX_DISTANCE_FILTER',6)),
          "category" => $category
        ]);

      } else {
        // no existe categoria
        return $this->incorrect(1000);
      }
      return $this->incorrect();

    }

    // por una parte devolveremos los mas populares, categorias, los últimos
    public function main()
    {
        // business search
        $business = Business::distance(auth()->user()->latitude, auth()->user()->longitude);
        // the near
        $businessNear = $business->get()->where('distance','<=',env('MAX_DISTANCE_FILTER',6));
        // pluck categories id of near
        $def = Category::whereIn('id',$businessNear->pluck('category_id'))->get();
        // ahora los mas populares
        $popular = $business->orderBy('distance','ASC')
                            ->get()
                            ->where('distance','<=',env('MAX_DISTANCE_FILTER',6))
                            ->take(5);
        // los más nuevos
        $products = [];
        if($plucked = $businessNear->pluck('id') and $plucked->count()>=1) {
          $products = Product::orderBy('views','DESC')
                             ->where([
                              ['hidden',false],
                              ['business_id',$plucked]
                            ])->take(5)->get();
        }
        // ahora si esta registrado o n'o
        $bool = auth()->user()->invited?? false;


        // devolvemos
        return $this->correct([
          "categories"  => $def,
          "popular"     => $popular,
          "logged"      => $bool,
          "newest"      => $products
        ]);
    }

    // sería una lista completa de los más populares ordenados por cercanía
    // si se pasa la categoría se devuelven de esa
    public function businessPopularNear($cat = null)
    {
        if($cat !== null and Category::find($cat)) {
          return $this->correct($this->businessFromCategory($cat)->paginate(50)->where('business.distance','<=',env('MAX_DISTANCE_FILTER',6)));
        }
        // si no pasamos catagoría entonces devolveremos por popularidad
        if($cat === null) {
          return $this->correct(Business::distance(auth()->user()->latitude, auth()->user()->longitude)->paginate(50));
        }
    }

    // para facilitar el trabajo de front podemos pasar las llamadas principales
    // a través de un string, no adivinando sus id's
    public function menuByName(String $menuName)
    {
      if($cat = Category::where('name',$menuName)->first()) {
          return $this->menu($cat->id);
      } else {
        return $this->incorrect(1000);
      }
    }

    // search only by business
    public function searchOnlyBusiness(Request $request)
    {
        $business = Business::distance(auth()->user()->latitude, auth()->user()->longitude)->where('name','like', "%$request->search%")->get();
        return $this->correct($business);

    }

    // para buscar
    public function search(Request $request)
    {
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.search')) {
        return $this->incorrect(0,$missings);
      }
      // si es solo de profesional cambiamos de funcion
      if($request->only_business) {
        return $this->searchOnlyBusiness($request);
      }
      // ahora filtramos
      $products = Product::with('business')->where('hidden',false);


      // $products = $products->where("distance",'<=',9);
      // $products = Product::with('businessDistanced');
      // apllicamos el texto
      if($request->has('search')) {
        $products = $products->where('name','like', "%$request->search%");
      }
      if($request->expensive_first) {
        $products = $products->orderBy('price','DESC');
      }
      if($request->cheapest_first) {
        $products = $products->orderBy('price','ASC');
      }
      if($request->newest) {
        $products = $products->orderBy('created_at','DESC');
      }

      // // BUG: FALTARÍA RELEVANTES


      // Ahora filtramos por si busca por categoría o subcategorias
      if($request->has('category') and $categoria = Category::find($request->category)){
        $products = $products->whereIn('category_id',$categoria->subCategories()->pluck('id'))
                             ->orWhere('category_id',$categoria->id);
      }
      // prices
      if($request->has('price_from') and $request->has('price_to')){
        $products = $products->where([
          ['price','<=',$request->price_to],
          ['price','>=',$request->price_from],
        ]);
      }
      // then execute the order
      $products = $products->take(100)->get();
      // de los resultados solo que esten a 100 km a la redonda
      $products = $products->where('business.distance','<=', env('MAX_DISTANCE_FILTER',100000000));


      //
      return $this->correct($products);
    }

    // devuelve las categorias
    public function categories()
    {
      return $this->correct($this->mainCategories());
    }


    //
    public function image(Request $request)
    {
      if($request->has('images')) {
        foreach ($request->images as $imagee) {
          // echo "string";
          $image = new Image();
          $image->name = $imagee->getClientOriginalName();
          $image->name = pathinfo($image->name, PATHINFO_FILENAME);
          $image->create($imagee,'categories',true);
          $image->refresh();
        }
        //
        return $this->correct($image);
      }
      //
      return $this->incorrect();
    }


    public function remove($image_id)
    {
      // primero si existe
      if(!$image = Image::find($image_id)) {
        return $this->incorrect(1204);
      }
      //
      if(auth()->user()->type == "business") {
        $image->delete();
        return $this->correct();
      }
      //
      return $this->incorrect(4);

    }

    // add image to the business
    public function addImage($business_id, Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addImage')) {
        return $this->incorrect(0,$missings);
      }
      $business = Business::find($business_id);
      //
      $new = new Image();
      $new->create($request->image,"business");
      $new->save();
      $business->images()->save($new);
      $new->refresh();
      return $this->correct($new);
    }

    public function testeo($business_id)
    {
      broadcast(new StripeEvent($business_id));
      return $this->correct();
    }


    public function condiciones()
    {
      return view('legal.condiciones');
    }

    public function sendNoti(Request $request)
    {
      $user = auth()->user();
      $user->send([
        "title"   => "Hola $user->name",
        "body"    => "Esto es un mensaje de prueba",
        "sound"   => "default",
        "type"    => "default"
      ]);
    }

    public function reserve(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.reserve')) {
        return $this->incorrect(0,$missings);
      }
      return $this->correct();
    }

    // listamos los descuentos disponibles
    public function listDiscounts()
    {
      return $this->correct(Discount::orderBy('created_at','desc')->get());
    }

    // listamos los descuentos disponibles
    public function addDiscount($discount_id)
    {
      if(!$discount = Discount::find($discount_id)) {
        return $this->incorrect(1400);
      }
      if(!$discount->validateDicount()) {
        auth()->user()->discount_id = null;
        auth()->user()->save();
        return $this->incorrect(1401);
      }
      auth()->user()->discount_id = $discount_id;
      auth()->user()->save();
      return $this->correct($discount);

    }

    public function deliver($purchase_id)
    {
      if(auth()->user()->type !== "admin") {
        return redirect('');
      }
      //
      if(!$purchase = Purchase::find($purchase_id)){
        return redirect('');
      }
      //
      $purchase->completed = true;
      $purchase->status = "delivered";
      $purchase->orders()->update([
        "status" => "delivered"
      ]);
      $purchase->save();
      return redirect('/admin');

    }

    public function purchaseView($purchase_id)
    {
      if(auth()->user()->type !== "admin") {
        return redirect('');
      }
      if(!$purchase = Purchase::find($purchase_id)){
        return redirect('');
      }
      return view('admin.layouts.addPurchase')->with('purchase',$purchase);
    }











}
