<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE STRIPE
	|--------------------------------------------------------------------------
	|
	*/
  Route::any('/stripe/add',                             'StripeController@addCard');
  Route::any('/pay',                                    'StripeController@pay');
  Route::any('/stripe/create/business',                 'StripeController@returnAndCreate');
