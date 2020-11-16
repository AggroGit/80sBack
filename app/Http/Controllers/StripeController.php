<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\StripeEvent;
use App\User;

class StripeController extends Controller
{
    // VIEW for add card, thats only give us the id of the new card
    public function addCardView($value='')
    {
        return view('stripe.addCard')->with('noTypeScript',true);
    }

    // VIEW for pay
    public function payView()
    {
      return view('stripe.addCard')->with('noTypeScript',true);
    }

    // pay a cuantity
    public function pay(Request $request)
    {
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.pay')) {
        return $this->incorrect(0,$missings);
      }
      if (auth()->user()->hasDefaultPaymentMethod()) {
        $user = auth()->user();
        $cantidad = 100*$request->amount;
        $stripeCharge = $user->charge($cantidad, $user->defaultPaymentMethod()->id);
        return $this->correct($stripeCharge->asStripePaymentIntent());
      } else {
        return $this->incorrect(200);
      }
    }

    // with the card id we add a card and set it by default
    public function addCard(Request $request)
    {
      $a = $request->all();
      $id =  array_keys($a)[0];
      auth()->user()->addPaymentMethod($id);
      auth()->user()->updateDefaultPaymentMethod(auth()->user()->paymentMethods()->first()->id);
      return $this->correct();

    }

    // we return of the Stripe url and then we create the user account
    public function returnAndCreate(Request $request)
    {
      // first we have to recive the data
      if ($request->has('code') and $request->has('state')) {
        // now we have to retrive the user by the state
        if ($user = User::where('temporal_token',$request->state)->first()) {
          // if exists then we create and asociate
          if ($r = $user->createAccount($request->code)) {
            $user->refresh();
            // lanzamos el socket
            $user->business->verified = true;
            $user->business->save();
            $user->save();
            broadcast(new StripeEvent($user->business->id));
            return redirect('/');
            return $this->correct($user);
          }

        }
      }
      return redirect('/');
      dd($request);
      return $this->correct($request);
    }

    public function urlToCreate()
    {
      if ($r = auth()->user()->createAccountURL() and $r !== false) {
        return $this->correct($r);
      } else {
        return $this->incorrect(809);
      }

    }





}
