<?php

use Illuminate\Support\Facades\Route;
use App\Navy\Enterprise;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// rutas de auth de laravel
Auth::routes();

Enterprise::admin();
Enterprise::staPerpetuaAdmin();


Route::get('/',                               'HomeController@start');
Route::get('/logout',                         'Auth\AuthController@logout');
Route::get('/password',                       'Auth\AuthController@forgetView');
Route::post('/password',                      'Auth\AuthController@changePass');
Route::get('/news/{id}',                      'NewsController@newView');


Route::get('/mail',            'TestController@testMail');



// la ruta que crea el usuario stripe business
Route::any('/stripe/return',                              'StripeController@returnAndCreate');
// default images
Route::any('/storage/business/{image_id}/{imagee}',       'HomeController@notFound');
Route::any('/storage/{type}/{image_id}/{imagee}',         'HomeController@notFound');

Route::get('/available-locals/map',                     'BusinessMapController@vueView');
Route::get('/available-locals',                         'BusinessMapController@vueView');
Route::get('/available-locals/{id}',                    'BusinessMapController@OnlyOne');
Route::get('/legal/collblanc/terminos-condiciones-app-mercat-collblanc',                    'HomeController@condiciones');
