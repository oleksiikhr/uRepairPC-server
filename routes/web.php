<?php

// FIXME Example
Route::get('/', function () {
    \App\Events\NewEvent::dispatch(['test' => '123']);
    return response()->json(['test' => 123]);
});

/*
 *  Websocket
 */

Route::get('/websocket', 'WebsocketController@index');

// SERVICE
Route::get('/service/windows', 'ServiceController@getWindows');

// Auth
Route::post('/websocket/auth/user', 'WebsocketController@userAuth');
Route::post('/websocket/auth/pc', 'WebsocketController@pcAuth');
Route::post('/websocket/auth/pc/key', 'WebsocketController@pcKey');
