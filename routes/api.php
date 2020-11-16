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

Enterprise::staPerpetua();
Enterprise::chats();
Enterprise::auth();
Enterprise::business();

Route::any('/notification',                     'TestController@sendPush');

// APLICACION
// principal
Route::any('/main',                             'HomeController@main');
Route::any('/main/name/{menuName}',             'HomeController@menuByName');
Route::any('/main/{cat}',                       'HomeController@menu');
Route::any('/main/{cat}/{subcat}',              'HomeController@menu');
// populares de categoría
Route::any('/popular/{cat}',                    'HomeController@businessPopularNear');
Route::any('/popular',                          'HomeController@businessPopularNear');
// business
Route::any('/business/{business_id}',           'ProductsContoller@business');
//
Route::any('/business/{business_id}/sections',  'ProductsContoller@getSections');
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
// news
Route::any('/news',                              'NewsController@getNews');
// report
Route::any('/report/{user_id}',                  'ChatsController@report');


// categorias
Route::any('/search',                           'HomeController@search');
Route::any('/test/{category_id}',               'HomeController@image');

Route::any('/test',               'HomeController@sendNoti');


});
