<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Jobs\sendMail;
use App\Mail\OrdersMail;
use App\Business;
use App\Purchase;
use App\Order;
use App\User;


class TestController extends Controller
{
    //



    public function sendPush(Request $request)
    {
      
    }

    public function testMail()
    {
      $orders = Order::all();
      $purchase = Purchase::find(1);
      $data = [
        "title" =>  "Su órden de Merco",
      ];
      sendMail::dispatch(new OrdersMail($orders,$purchase),'puripiturm@hotmail.com');
    }
}
