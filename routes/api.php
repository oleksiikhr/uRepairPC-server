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
    Route::apiResource('users', 'UserController');
    Route::group(['prefix' => 'users'], function () {
        Route::get('get/image', 'UserController@getImage');
        Route::post('{id}/email', 'UserController@updateEmail')->where('id', '[0-9]+');
        Route::post('{id}/password', 'UserController@updatePassword')->where('id', '[0-9]+');
        Route::post('{id}/image', 'UserController@updateImage')->where('id', '[0-9]+');
        Route::delete('{id}/image', 'UserController@destroyImage')->where('id', '[0-9]+');
    });

    /*
     * Section: Workers (users*)
     */
    Route::group(['prefix' => 'workers'], function () {
        Route::get('/', 'WorkerController@index');
    });

    /*
     * Section: Equipments
     */
    Route::apiResource('equipments', 'EquipmentController');
    Route::apiResource('equipments/types', 'EquipmentTypeController');
    Route::apiResource('equipments/manufacturers', 'EquipmentManufacturerController');
    Route::apiResource('equipments/models', 'EquipmentModelController');

});
