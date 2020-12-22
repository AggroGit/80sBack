<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\UploadedFile;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Jobs\jobNotify;
use App\Notification;
use App\Association;
use App\Purchase;
use App\Message;
use App\Image;
use App\User;
use Storage;


class AuthController extends Controller
{

    // return the logged user
    public function currentUser(Request $request)
    {
      // return $this->correct($request->user());
      if (auth()->user())
        return $this->correct(User::with('shoppingCart')->find(auth()->user()->id));
      else
        return $this->incorrect();
    }

    /**
     * make the login via API
     *
     * @return Response
     */
    public function login(Request $request)
    {
      // si es por redes sociales
      if ($request->has('social_name')) {
          return $this->rrssLogin($request);
      }
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.login')) {
        return $this->incorrect(0,$missings);
      }
      // ya verificados vemos si existe el user, en caso que si autenticamos,
      // sino error
      if (!Auth::attempt([
        "email"     => $request->email,
        "password"  => $request->password
        ])) {
        return $this->incorrect(3);
      }
      auth()->user()->device_token = $request->device_token;
      auth()->user()->save();
      // devolvemos el usuario autenticado
      return $this->correct(auth()->user()->creteTokenUser());

    }

    /**
     * make the register via API
     *
     * @return Response
     */
    public function register(Request $request)
    {
      if ($request->has('social_name')) {
          return $this->rrssRegister($request);
      }
      // si hay un error saltamos el response con los mensajes
      if ($missings = $this->hasError($request->all(),'validation.register')) {
        if ($u = User::where('email',$request->email)->first()) {
           return $this->incorrect(2);
        }
        return $this->incorrect(0,$missings);
      }
      // creamos el usuario
      $new = new User($request->all());
      $new->password = bcrypt($request->password);

      if($request->has('profesional') and $request->profesional == true) {
        $new->type = "business";
        echo "si";
      }
      //
      if($request->has('association')) {
        if($ass = Association::where('searchName',$request->association)->first()) {
          $new->association_id = $ass->id;
        }
      }
      $new->save();
      // devolvemos logeado
      return $this->login($request);
    }

    public function chats()
    {
       return $this->correct(auth()->user()->chats);
    }

    public function rrssRegister(Request $request, $own = false)
    {
      if ($missings = $this->hasError($request->all(),'validation.register_rrss')) {
        return $this->incorrect(0,$missings);
      }
      //
      if(User::where('social_token',$request->social_token)->first()) {
        return $this->incorrect(2);
      }
      if($request->social_user_email !== null) {
          if(User::where('email', $request->social_user_email)->first()) {
            return $this->incorrect(2);
          }
      }
      $new = new User($request->all());
      $new->name = $request->social_user_name;
      $new->email = $request->social_user_email?? $random = Str::random(10)."@merco.app";
      $new->social_token = $request->social_token;
      $new->social_name = $request->social_name;
      if($request->has('profesional') and $request->profesional) {
        $new->type = "business";
      }
      //
      if($request->has('association')) {
        if($ass = Association::where('searchName',$request->association)->first()) {
          $new->association_id = $ass->id;
        }
      }
      $new->device_token = $request->device_token;
      $new->save();
      if($request->has('profileImage')) {
        //
        $image = new Image();
        $image->createFromUrl($request->profileImage,"user");
        $new->save();
        $new->image_id =$image->id;
        $new->save();
      }
      return $this->correct($new->creteTokenUser());
    }

    public function rrssLogin(Request $request, $own = false)
    {
      if ($missings = $this->hasError($request->all(),'validation.login_rrss')) {
        return $this->incorrect(0,$missings);
      }
      if($user = User::where([
        ['social_token',  $request->social_token],
        ['social_name',   $request->social_name],
        // ['email',         $request->social_user_email]
      ])->first()) {
        $user->device_token = $request->device_token;
        $user->save();
        return $this->correct($user->creteTokenUser());
      }
      else {
        return $this->rrssRegister($request,true);
      }
    }

    public function invitedToCurrent(Request $request)
    {
      if ($request->has('social_name')) {
          return $this->rrssRegister($request);
      }
      if ($missings = $this->hasError($request->all(),'validation.register')) {
        if ($u = User::where('email',$request->email)->first()) {
           return $this->incorrect(2);
        }
        return $this->incorrect(0,$missings);
      }

      if (!auth()->user()->invited) {
        return $this->incorrect(9);
      }
      if($request->has('profesional') and $request->profesional) {
        auth()->user()->type = "business";
      }
      auth()->user()->device_token = $request->device_token;
      auth()->user()->save();
      // si llegamos hasta aqui la validacion es correcta,
      // ahora convertimos al usuario
      auth()->user()->invitedToCurrent($request);
      return $this->correct(auth()->user()->creteTokenUser());

    }

    public function createInvited(Request $request)
    {
      $token = uniqid();
      $new = new User();
      $new->name = "invited";
      $new->email = "$token@merco.app";
      $new->invited = true;
      if($request->has('association')) {
        if($ass = Association::where('searchName',$request->association)->first()) {
          $new->association_id = $ass->id;
        }
      }
      $new->save();
      return $this->correct($new->creteTokenUser());
    }

    public function completeShoppingCart()
    {
      return $this->correct(auth()->user()->completeShoppingCart());
    }

    public function allNotifications()
    {
      $notifications = auth()->user()->allNotifications();
      $notis = $notifications->get();
      $notifications->delete();
      return $this->correct($notis);
    }

    public function myCredtCards()
    {
      return $this->correct(auth()->user()->cards());
    }

    public function buy(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.buy')) {
        return $this->incorrect(0,$missings);
      }
      $purchase = null;
      if($error = auth()->user()->buyShoppingCart($request,$purchase) and $error !== true) {
        return $this->incorrect($error);
      }
      auth()->user()->openChatToBusinessFromPurchase($purchase);
      $p = Purchase::find($purchase);
      $p->sendChat();
      return $this->correct($p);
    }

    // get all the purchases
    public function history()
    {
      $purchases = auth()->user()->purchases;
      foreach ($purchases as $purchase) {
        $purchase->url = auth()->user()->generateUrlRoute($purchase->orders());
      }
      return $this->correct($purchases);
    }

    // add a credit card from id
    public function addCard(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.addCreditCard')) {
        return $this->incorrect(0,$missings);
      }
      try {
        auth()->user()->addPaymentMethod($request->id);
        auth()->user()->updateDefaultPaymentMethod(auth()->user()->paymentMethods()->first()->id);
      } catch (\Exception $e) {
        return $this->incorrect(206);
      }
      return $this->correct(auth()->user());

    }

    // edit the user
    public function editUser(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.editUser')) {
        return $this->incorrect(0,$missings);
      }

      //
      $user = auth()->user();
      if($request->has('password'))
      $request->password = bcrypt($request->password);
      //
      // si el usuario quiere cambiar la fecha de nacimiento
      if($request->has('birthday')) {
        if($user->birthday_changed) {
          return $this->incorrect(15);
        }
        $user->birthday_changed = true;
        $user->send([
          "title"   => 'Descuento disponible',
          "body"    => "¡Felicidades! durante el dia de hoy tienes un descuento en tus pedidos.",
          "sound"   => "default",
          "badge"   => 1,
          "type"    => "discount"
        ],$request->birthday);

      }
      $user->fill($request->all());
      // y la imagen
      if($request->has('profileImage')) {
        // borramos la imagen anterior
        if($user->image !== null) {
          $user->image->destroyImage();
          $user->image->delete();
        }
        // hacemos la nueva
        $id = $user->id;
        $new = new Image();
        $new->create($request->profileImage,"user");
        $new->save();
        $user->image_id = $new->id;
      }
      $user->save();
      $user->refresh();
      return $this->correct($user);
    }

    public function location(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.location')) {
        return $this->incorrect(0,$missings);
      }
      auth()->user()->latitude = $request->latitude;
      auth()->user()->longitude = $request->longitude;
      auth()->user()->save();
      return $this->correct(auth()->user());
    }

    public function removeOrders(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.removeOrders')) {
        return $this->incorrect(0,$missings);
      }
      // recorremos todos los ids y lo vamos borrando de nuestro carrito
      auth()->user()->shoppingCart()->whereIn('id',$request->ids)->delete();
      return $this->correct(auth()->user()->completeShoppingCart());
    }

    public function changePassword(Request $request)
    {
      // envia un correo que solicita cambiar la contraseña
      if ($missings = $this->hasError($request->all(),'validation.resetPass')) {
        return $this->incorrect(0,$missings);
      }
      //
      if($user = User::where('email',$request->email)->first()) {
        $user->forgetPass();
      }
      return $this->correct();
    }

    // vista para resetear la contraseña
    public function forgetView(Request $request)
    {
      // get the user by token
      if($request->has('token') and $user = User::where('remember_token',$request->token)->first()) {
        $user->remember_token = md5(uniqid(rand(), true));
        $user->save();
        return view('authh.reset')->with('token',$user->remember_token);
      }
      return redirect('/');
    }

    public function changePass(Request $request)
    {
      if ($missings = $this->hasError($request->all(),'validation.changePass')) {
        return redirect('/');
      }
      if($request->has('token') and $user = User::where('remember_token',$request->token)->first()) {
        $user->password = bcrypt($request->password);
        $user->save();
        return redirect('/')->with('correct','Se ha cambiado correctamente su contraseña');
      }
      return redirect('/');
    }

    public function logout()
    {
      if(auth()->user()) {
        Auth::logout();
      }
      return redirect('/');
    }
}
