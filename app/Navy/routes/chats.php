<?php



	/*
	|--------------------------------------------------------------------------
	| RUTAS DE  CHATS
	|--------------------------------------------------------------------------
	|
	*/


    Route::any('/chats',             'ChatsController@chats');
    // open chat
    Route::any('/business/{business_id}/chat',      'ProductsContoller@BusinessChat');
    Route::any('/chats/{user_id}/chat',             'ChatsController@checkChatExist');

    // chats
    Route::group(['middleware' => 'existChat'], function()
    {
    Route::post('/{chat_id}/send',        'ChatsController@send');
    Route::any('/chat/{chat_id}',         'ChatsController@chat');
    Route::any('/chat/{chat_id}/block',   'ChatsController@block');
    Route::any('/chat/{chat_id}/unblock', 'ChatsController@unblock');
    Route::any('/chat/{chat_id}/messages','ChatsController@messages');

    });
