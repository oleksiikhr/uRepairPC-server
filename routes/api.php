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
    Route::group(['prefix' => 'settings'], function () {
        Route::post('frontend', 'SettingsFrontendController@store');
    });

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
    Route::apiResource('equipments', 'EquipmentController');
    Route::group(['prefix' => 'equipments'], function () {
        Route::apiResource('types', 'EquipmentTypeController');
        Route::apiResource('manufacturers', 'EquipmentManufacturerController');
        Route::apiResource('models', 'EquipmentModelController');
        Route::apiResource('{equipment}/files', 'EquipmentFileController');
    });

    /*
     * Section: Roles
     */
    Route::apiResource('roles', 'RoleController');
    Route::group(['prefix' => 'roles'], function () {
        Route::put('{role}/permissions', 'RoleController@updatePermissions');
    });

    /*
     * Section: Permissions
     */
    Route::get('permissions', 'PermissionController@index');

    /*
     * Section: Requests
     */
    Route::group(['prefix' => 'requests'], function () {
        Route::apiResource('statuses', 'RequestStatusController');
        Route::apiResource('priorities', 'RequestPriorityController');
    });

});
