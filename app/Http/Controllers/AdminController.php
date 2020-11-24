<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Association;
use App\Business;
use App\Category;
use App\Product;
use App\Order;
use App\Image;
use App\User;

class AdminController extends Controller
{
    //
    public function listModel($modelName)
    {
      $model = $this->getModel($modelName);
      if(!$model) {
        return back();
      }
      $tabletate = $model::tabletate($model::orderBy('created_at','desc')->get());
      //
      return view('admin.layouts.tableList')->with([
        'tabletate' => $tabletate,
        'noTypeScript'  => true
      ]);
    }



    public function addModel($modelName)
    {
      $model = $this->getModel($modelName);
      $tabletate = $model::tabletate();
      //
      return view('admin.layouts.tableAdd')->with([
        'tabletate' => $tabletate,
        'model'     => $model
      ]);
    }

    public function editModelExists($model,$id)
    {
      $model = $this->getModel($model)->find($id);

      if($cat = $model::find($id)) {
        $tabletate = $model::tabletate($cat);
        //
        return view('admin.layouts.tableAdd')->with([
          'tabletate' => $tabletate,
          'model'     => $model
        ]);
      }

    }

    public function addDataModel($modelName, Request $request)
    {
      $model = $this->getModel($modelName);
      if($request->id !== false and $request->id !== null and $model::find($request->id))  {
        $model = $model::find($request->id);
      }
      // campos
      foreach ($request->all() as $key => $value) {
        if($key !== '_token') {
          if (Schema::hasColumn($model->getTable(), $key)) {
            // excepciones
            if($key == "price") {
              $value = str_replace(',','.',$value);
            }
            $model[$key] = $value;

          }

          else {
              $model->save();
              // para un multiselect
              if($key !== "image")
              $a = $model->{$key.'s'}()->sync($value);
          }


        }

      }
      //
      if($request->has('image')) {
        if (Schema::hasColumn($model->getTable(), 'image_id')) {
          $image = new Image();
          $image->create($request->image,$modelName);
          $model->image_id = $image->id;
        } else {
          try {
            $image = new Image();
            $image->create($request->image,$modelName);
            $model->images()->save($image);
          } catch (\Exception $e) {}

        }

      }


      $model->save();
      return redirect("admin/$modelName");
    }

    public function deleteDataModel($modelName,$id)
    {
      $model = $this->getModel($modelName);
      if($model = $model::find($id))  {
        if($model->image)
        $model->image->destroyImage();
        if($model->images) {
          foreach ($model->images as $image) {
            $image->destroyImage();
          }
        }
        $model->delete();
      }
      return redirect("admin/$modelName");
    }

    public function dashboard()
    {
      return view('admin.layouts.dashboard')->with([
        'numProducts' => Product::count(),
        'numBusiness' => Business::count(),
        'numUsers'    => User::count(),
        'numOrders'   => Order::count(),
        'numImages'   => Image::count()
      ]);
    }

    //











    public function catsView()
    {
      $categories = Category::with('subCategories')->paginate(50);
      // dd($categories);
      return view('admin.categories')->with("categories",$categories);
    }

    public function getModel($name)
    {
      // code...
      $className = 'App\\'.ucwords($name);

      if(class_exists($className)) {
          $model = new $className;
          return $model;
      }
      return false;
    }

    public function removeCat($id)
    {
      // code...
      if($cat=Category::find($id)) {
        if($cat->image)
        $cat->image->destroyImage();
        $cat->delete();
      }
      return redirect('/admin/categories');

    }

    public function catView($id)
    {
      if($cat = Category::find($id)) {
        $main = Category::where('category_id',null)->where('id','!=',$id)->get();
        return view('admin.category')->with('category',$cat)->with('main',$main);
      }
      return redirect('/admin/categories');
    }

    public function updateCat(Request $request)
    {
      if($category = Category::find($request->id)) {
        $request->category_id?? null;
        $category->fill($request->all());
        if($request->has('image')) {
          if($category->image_id == null) {
            $image = new Image();
            $image->create($request->image,'categories');
            $category->image_id = $image->id;
          } else {
            // dd($category->image);
            $category->image->updateImage($request->image);
          }
        }
        $category->save();
        return redirect("/admin/categories");
      }

    }

    public function newCat(Request $request)
    {
      $new = new Category($request->all());
      if($request->has('image')) {
          $image = new Image();
          $image->create($request->image,'categories');
          $new->image_id = $image->id;

      }
      $new->save();
      return redirect("/admin/categories");

    }


}
