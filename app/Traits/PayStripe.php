<?php





namespace App\Traits;



/**
 * Stripe Object for sending notifications
 *
 */
trait PayStripe
{

  // create the stripe object for the queries
  public function stripe()
  {
    return $stripe = new \Stripe\StripeClient(
      env('STRIPE_SECRET')
    );
  }

  public function stripeApi()
  {
    return $stripe = \Stripe\Stripe::setApiKey(
      env('STRIPE_SECRET')
    );
  }

  // all the carges of a costumer
  public function charges()
  {
    return $this->stripe()->charges->all(['customer' => $this->stripe_id]);
  }

  // create a connect account URL
  public function createAccountURL()
  {
    // if the user has not the reciver id
    if ($this->stripe_reciver_id == null) {
      // we create a token
      $this->temporal_token = uniqid().md5(rand(1, 10) . microtime());
      // we have to return the url with some params
      $base = "https://dashboard.stripe.com/express/oauth/authorize?response_type=code&client_id=ca_HodJq0b7bJdKCJISWAZmkqMhbnaFxOhC&scope=read_write"
      ."stripe_user[email]=$this->email&"
      ."stripe_user[first_name]=$this->name&"
      ."stripe_user[last_name]=$this->surnames&"
      ."stripe_user[phone_number]=$this->phone&"
      ."redirect_uri=".url('/stripe/return')."&"
      ."state=$this->temporal_token&";
      // save for the token
      $this->save();
      return $base;
    }
    return false;
  }

  // with the code we have to create the account for recive money
  public function createAccount($code)
  {
    $response = $this->stripeApi();
    try {
      $r = \Stripe\OAuth::token([
         'grant_type' => 'authorization_code',
         'code' => $code,
        ]);
        // if all okay we save the token
        if ($id = $r->stripe_user_id) {
          $this->stripe_reciver_id = $id;
          $this->save();
          return true;
        }
    } catch (\Exception $e) {
      return false;
    }


    return $r;

  }


  // 🐀 Ratatouillea, A Mr Ego
  // no le gustó el plato 🍛
  public function devolver($idPago,$cantidad = null)
  {
    try {
      if(is_null($cantidad)) {
        $this->refund($idPago);
        return true;
      } else {
        // en centimos
        $cantidad = $cantidad*100;
        $this->refund($idPago,[
          'amount' => $cantidad,
        ]);
        return true;
      }
    } catch (\Exception $e) {
      return false;
    }




  }

  // return the login link of Stripe
  public function loginLink()
  {
    $stripe = $this->stripeApi();
    $r = \Stripe\Account::createLoginLink($this->stripe_reciver_id);
    return $r;
  }

  public function Pay($amount,$transferGroup = null)
  {
    //if($this->canReciveMoney()) {
    try {
      $stripe = $this->stripeApi();
      $transfer = \Stripe\Transfer::create([
        "amount"          => $amount*100,
        "currency"        => "eur",
        "destination"     => $this->stripe_reciver_id,
        "transfer_group"  => $transferGroup
      ]);
      return $transfer;
    } catch (\Exception $e) {
      return false;
    }

  }

  public function deleteClient()
  {
      if($this->stripe_id !== null) {
        $stripe = $this->stripe();
        $stripe->customers->delete(
          $this->stripe_id,
          []
        );
      }
  }

  public function getStripeCommisionFromCharge($charge)
  {
    $balance = null;
    $fee = false;
    $balance = $charge->charges->first()->balance_transaction;
    if($balance !== null) {
      // sacamos el objeto
      $stripe = $this->stripe();
      $balanceObject = $stripe->balanceTransactions->retrieve(
        $balance,
        []
      );
      $fee = ($balanceObject->fee)/100;
    }
    return $fee;


  }





}
