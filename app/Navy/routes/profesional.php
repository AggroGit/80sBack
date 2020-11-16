<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE Profesional y business
	|--------------------------------------------------------------------------
	|
	*/
  Route::any('/prof',                                         'profesionalController@index');
  Route::any('/prof/create',                                  'profesionalController@createBusiness');

  Route::group(['middleware' => 'isProf'], function()
  {


  Route::any('/prof/{business_id}',                               'profesionalController@business');
  Route::any('/prof/{business_id}/edit',                          'profesionalController@editBusiness');
  Route::any('/prof/{business_id}/image',                         'profesionalController@addImage');
  Route::any('/prof/{business_id}/product/create',                'profesionalController@createProduct');
  Route::any('/prof/{business_id}/product/remove',                'profesionalController@removeProducts');
  Route::any('/prof/{business_id}/product/{product_id}/edit',     'profesionalController@editProduct');
  Route::post('/prof/{business_id}/product/{product_id}/add',      'profesionalController@addingImageProduct');
  Route::any('/prof/{business_id}/products',                      'profesionalController@products');
  Route::any('/prof/{business_id}/history',                       'profesionalController@history');
  Route::any('/prof/{business_id}/purchase/{purchase_id}/cancel', 'profesionalController@cancelOrder');
  Route::any('/prof/{business_id}/purchase/{purchase_id}/deliver','profesionalController@deliverOrder');
  Route::any('/prof/{business_id}/stripeurl',                     'StripeController@urlToCreate');
  Route::any('/prof/{business_id}/ia',                            'IAController@recomendations');


  });
