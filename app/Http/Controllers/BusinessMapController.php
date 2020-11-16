<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class BusinessMapController extends Controller
{
    //

    public function vueView()
    {
      return view('localsMap.all');
    }

    public function OnlyOne($id)
    {

      return view('localsMap.one')->with('id',$id);
    }

    public function getData(Request $request)
    {
      $client = new Client();
        $q =($request->q);
       $res = $client->request('GET', 'https://opendata-ajuntament.barcelona.cat/data/api/action/datastore_search?resource_id=c897c912-0f3c-4463-bdf2-a67ee97786ac&limit=400&q='.$q, []);
        // echo $res->getStatusCode();
       // 200
       // echo $res->getHeader('content-type');
       // 'application/json; charset=utf8'
       $res = $res->getBody()->getContents();
       $res = json_decode($res);
       return $this->correct($res);
    }

}
