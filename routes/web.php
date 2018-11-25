<?php

// FIXME Example
Route::get('/', function () {
    \App\Events\NewEvent::dispatch(['test' => '123']);
    return response()->json(['test' => 123]);
});

/*
 * Auth
 */

Route::post('/auth', 'AuthController@login');
Route::post('/auth/logout', 'AuthController@logout');

/*
 * Service
 */

Route::get('/service/windows', 'ServiceController@getWindows');

/*
 *  Websocket
 */

Route::get('/websocket', 'WebsocketController@index');

// Auth
Route::post('/websocket/auth/user', 'WebsocketController@userAuth');
Route::post('/websocket/auth/pc', 'WebsocketController@pcAuth');
Route::post('/websocket/auth/pc/key', 'WebsocketController@pcKey');
