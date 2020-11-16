<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
class NewsController extends Controller
{
    //

    public function getNews()
    {
      // si tiene associacion la de la aso, sino todas
      $news = (auth()->user()->association_id)?
      $this->getPublicNews()->where('association_id',auth()->user()->association_id)->orWhere('association_id',null)->get():
      $this->getPublicNews()->get();
      //
      return $this->correct($news);
    }

    public function getPublicNews()
    {
      return News::where('publishAt','<=',now());
    }

    public function newView($id)
    {
      if($new = News::find($id)) {
        return view('new')->with('new',$new);
      }
    }


}
