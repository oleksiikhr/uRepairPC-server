<?php

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

/*
 * Section: Auth
 */
Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('forgot', 'AuthController@forgot');
        Route::post('refresh', 'AuthController@refresh');
    });

    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => ['jwt.auth']], function () {

    /*
     * Section: Users
     */
    Route::group(['prefix' => 'users'], function () {
        Route::apiResource('/', 'UserController');
        // TODO email, password
        Route::get('{id}/image', 'UserController@getImage')->where('id', '[0-9]+');
        Route::post('{id}/image', 'UserController@updateImage')->where('id', '[0-9]+');
        Route::delete('{id}/image', 'UserController@destroyImage')->where('id', '[0-9]+');
    });

});
