<?php

use Illuminate\Support\Facades\Route;

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

    // login and register
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    // password
    Route::post('/password/forgot', 'PasswordController@forgotPassword');
    Route::post('/password/change', 'PasswordController@changePassword');

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
    Route::get('/invoices', 'InvoiceController@getInvoices');
    Route::get('/invoice/{reference}', 'InvoiceController@getSingleInvoice');
    Route::post('/invoices', 'InvoiceController@postInvoice');

    //invoice payment
    Route::post('/payments/single', 'InvoiceController@singlePayment');
    Route::get('/payments/bulk', 'InvoiceController@bulkPayment');
    Route::get('/payments/callback', 'InvoiceController@invoiceStatus');

    // get web settings
    Route::get('/web-settings', 'WebSettingsController@index');
    Route::get('/live-chat', 'WebSettingsController@support');
    Route::get('/store', 'WebSettingsController@ecommStore');

});
