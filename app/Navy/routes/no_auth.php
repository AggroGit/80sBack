<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE LOGIN Y REGISTRO
	|--------------------------------------------------------------------------
	|
	*/

  // auth
  Route::any('/login',               'Auth\AuthController@login');
  Route::any('/register',            'Auth\AuthController@register');
  //
  Route::any('/request/invited',     'Auth\AuthController@createInvited');
