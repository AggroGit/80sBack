<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Navy\Enterprise;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// login and register routes
Enterprise::noAuth();


Route::any('/categories',                 'HomeController@categories');
Route::post('/image',                     'HomeController@image');
// Barcelona Open Data
Route::any('/open-data',                  'BusinessMapController@getData');
// Route::any('/open-data/{id}',             'BusinessMapController@getById');
Route::any('/password',                   'Auth\AuthController@changePassword');

// logged and necesary Stripe id
Route::group(['middleware' => ['auth:api','hasStripe']], function()
{

// Enterprise::staPerpetua();
// Enterprise::chats();
Enterprise::auth();
Enterprise::business();

Route::any('/discounts',                      'HomeController@listDiscounts'); //done
Route::any('/discounts/{discount_id}/add',    'HomeController@addDiscount'); //done


Route::any('/notification',                     'TestController@sendPush');


Route::any('/business',                         'ProductsContoller@business');
//
Route::any('/business/reserve',                 'HomeController@reserve');

//
Route::any('/business/sections',                'ProductsContoller@getSections');
// product t
Route::any('/product/{product_id}',             'ProductsContoller@getById');
// order by id
Route::any('/order/{order_id}',                 'OrderController@getById');
// product to cart
Route::any('/product/{product_id}/add',         'ProductsContoller@addToCart');
// edit from the cart
// remove from the cart
Route::any('/order/{order_id}/minus',            'OrderController@removeCart');
// remove all cart
Route::any('/order/{order_id}/remove',           'OrderController@removeAllCart');
// add one to cart
Route::any('/order/{order_id}/more',             'OrderController@addOne');
// edit order
Route::any('/order/{order_id}/edit',             'OrderController@editCart');
// images
Route::any('/image/{image_id}/remove',           'HomeController@remove');


Route::any('/test',               'HomeController@sendNoti');


});
