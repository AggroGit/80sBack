<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE
	|--------------------------------------------------------------------------
	|
	*/


// current user
Route::any('/auth',                             'Auth\AuthController@currentUser')->name('current');
Route::any('/auth/cards',                       'Auth\AuthController@buy');
Route::any('/auth/cards/add',                   'Auth\AuthController@addCard');
Route::post('/auth/edit',                       'Auth\AuthController@editUser');
Route::any('/auth/password',                    'Auth\AuthController@changePassword');
Route::any('/auth/chats',                       'Auth\AuthController@chats')->name('chatsCurrent');
Route::any('/auth/register',                    'Auth\AuthController@invitedToCurrent');
Route::any('/auth/shopping_cart',               'Auth\AuthController@completeShoppingCart');
Route::any('/auth/location',                    'Auth\AuthController@location');
Route::group(['middleware' => 'notInvited'], function()
{
Route::any('/auth/shopping_cart/buy',           'Auth\AuthController@buy');
Route::any('/auth/history',                     'Auth\AuthController@history');
});
Route::any('/auth/shopping_cart/remove_orders', 'Auth\AuthController@removeOrders');
Route::any('/auth/notifications',               'Auth\AuthController@allNotifications');
