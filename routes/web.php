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

    // Test
    Route::get('/', function() { return "something here"; });

    // login and register
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    // User
    Route::get('profile', 'UserController@profile');
    Route::post('/profile/update', 'UserController@updateProfile');
    Route::get('logout', 'UserController@logout');
    Route::post('token/refresh', 'UserController@refresh');

    // get list of verified school
    Route::get('schools', 'SchoolController@listVerifiedSchool');

    // get feetype for school
    Route::post('/school/feetype', 'SchoolController@getSchoolAndFeeType');

    // get fees for the school
    Route::post('/school/fees', 'SchoolController@getSchoolAndFees');

    //invoice
    Route::get('/invoice', 'InvoiceController@getInvoices');
    Route::get('/invoice/{reference}', 'InvoiceController@getSingleInvoice');
    Route::post('/invoice', 'InvoiceController@postInvoice');

    // password
    Route::post('/password/forgot', 'PasswordController@forgotPassword');
    Route::post('/password/change', 'PasswordController@changePassword');

});
