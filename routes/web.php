<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::group(['prefix' => 'api/v1'], function () use ($router) {

    Route::get('/', function() { return "something here"; });
    // login and register
    Route::post('/login', 'UserController@loginUser');
    Route::post('/register', 'UserController@registerUser');

    //user
    Route::get('/user/{id}', 'UserController@getUserDetails');
    Route::post('/user/update', 'UserController@updateUserDetails');

    // get list pf verified school
    Route::post('/schools', 'SchoolController@listVerifiedSchool');
    // get list pf verified school
    Route::post('/school/feetype', 'SchoolController@getSchoolAndFeeType');
    // get fees for the school
    Route::post('/school/fees', 'SchoolController@getSchoolAndFees');

    // password
    Route::post('/password/forgot', 'PasswordController@forgotPassword');
    Route::post('/password/change', 'PasswordController@changePassword');

});
