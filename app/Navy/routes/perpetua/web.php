<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE ADMIN
	|--------------------------------------------------------------------------
	|
	*/

  Route::group(['prefix'=>'perpetua','middleware' => ['auth','adminPerpetua']], function()
  {
    // CUSTOM
    Route::any('/admin/business/remove/{id}', 'profesionalController@removeBusiness');


    // Noticias
    Route::get('/admin',                            'staPerpetuaController@news');
    Route::get('/admin/news',                       'staPerpetuaController@news');
    Route::get('/admin/news/add',                   'staPerpetuaController@addNewView');
    Route::get('/admin/news/edit/{id}',             'staPerpetuaController@addNewView');
    Route::get('/admin/news/remove/{id}',           'staPerpetuaController@removeNew');
    Route::post('/admin/news/add',                  'staPerpetuaController@addNew');
    // Notificaciones
    Route::get('/admin/notifications',              'staPerpetuaController@staNotis');
    Route::get('/admin/notifications/add',          'staPerpetuaController@addNotiView');
    Route::get('/admin/notifications/edit/{id}',    'staPerpetuaController@addNotiView');
    Route::post('/admin/notifications/add',         'staPerpetuaController@addNoti');
    Route::any('/admin/notifications/remove/{id}', 'staPerpetuaController@removeNoti');
    // rasca
    Route::get('/admin/scratch',                    'staPerpetuaController@staRasca');
    Route::get('/admin/scratch/add',                'staPerpetuaController@editRascaView');
    Route::get('/admin/scratch/edit/{id}',          'staPerpetuaController@editRascaView');
    Route::get('/admin/scratch/remove/{id}',        'staPerpetuaController@removeRasca');
    Route::post('/admin/scratch/add',               'staPerpetuaController@addOrEditRasca');
    // Encuestas
    Route::get('/admin/encuestas',                  'staPerpetuaController@encuestas');
    Route::post('/admin/encuestas/add',              'staPerpetuaController@addEncuestas');
    // Usuarios
    Route::get('/admin/users',                      'staPerpetuaController@users');
    Route::get('/admin/user/edit/{id}',             'staPerpetuaController@addUserView');



  });
