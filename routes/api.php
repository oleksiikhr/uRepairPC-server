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

Route::get('settings/frontend', 'SettingsFrontendController@index');

/*
 * Section: Auth
 */
Route::group(['prefix' => 'auth'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('refresh', 'AuthController@refresh');
    });

    Route::group(['middleware' => ['jwt.auth']], function () {
        Route::post('logout', 'AuthController@logout');
    });
});

Route::group(['middleware' => ['jwt.auth']], function () {

    /*
     * Section: Settings
     */
    Route::post('settings/frontend', 'SettingsFrontendController@store');

    /*
     * Section: Users
     */
    Route::apiResource('users', 'UserController');
    Route::group(['prefix' => 'users'], function () {
        Route::put('{user}/email', 'UserController@updateEmail');
        Route::put('{user}/password', 'UserController@updatePassword');
        Route::put('{user}/roles', 'UserController@updateRoles');
        Route::get('{user}/image', 'UserController@getImage');
        Route::post('{user}/image', 'UserController@setImage');
        Route::delete('{user}/image', 'UserController@deleteImage');
    });

    /*
     * Section: Equipments
     */
    Route::apiResource('equipments/types', 'EquipmentTypeController');
    Route::apiResource('equipments/manufacturers', 'EquipmentManufacturerController');
    Route::apiResource('equipments/models', 'EquipmentModelController');
    Route::apiResource('equipments', 'EquipmentController');
    Route::apiResource('equipments/{equipment}/files', 'EquipmentFileController');

    /*
     * Section: Roles
     */
    Route::apiResource('roles', 'RoleController');

    /*
     * Section: Permission
     */
    Route::get('permissions', 'PermissionController@index');

});
