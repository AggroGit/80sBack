<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE ADMIN
	|--------------------------------------------------------------------------
	|
	*/

  Route::group(['middleware' => ['auth:api']], function()
  {
    Route::any('/perpetua/auth/shopping_cart/discounts',                'staPerpetuaController@listDiscounts'); //done
    Route::any('/perpetua/business/{business_id}/discounts/add',        'staPerpetuaController@addDiscount'); //done
    Route::any('/perpetua/business/{business_id}/discounts',            'staPerpetuaController@listDiscountsByBusiness'); //done
    Route::any('/perpetua/business/{business_id}/reviews/add',          'staPerpetuaController@addReview'); //done
    Route::any('/perpetua/business/{business_id}/reviews',              'staPerpetuaController@reviewsList'); //done
    Route::any('/perpetua/auth/shopping_cart',                          'staPerpetuaController@shoppingCartSta'); // done
    Route::any('/perpetua/auth/scratches',                              'staPerpetuaController@listScratch'); // done
    Route::any('/perpetua/scratches/apply/{id}',                        'staPerpetuaController@applyScratch'); // done
    Route::any('/perpetua/discount/apply',                              'staPerpetuaController@applyDiscount'); // done
    Route::any('/perpetua/quiz',                                        'staPerpetuaController@quiz'); // done

  });
