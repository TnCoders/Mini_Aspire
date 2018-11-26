<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


/**
 * User Login Route API Endpoint
 */
Route::post('login', 'AuthController@login');

/**
 * Employee Login And Register API Endpoint:
 */
Route::post('employee/register', 'AuthemployeeController@register')->name('employee.register');
Route::post('employee/login', 'AuthemployeeController@login')->name('employee.register');

/**
 * ! APi Resource Routes
 */
Route::apiResource('users', 'UserController');
Route::get('loans/list/', 'LoanController@list')->name('loans.list');
Route::apiResource('loans', 'LoanController');

/*
 * Route API Endpoint For  User To Make Repayment For their Loan:
 */
Route::post('repayments/loan/{loan}', 'RepaymentController@create')->name('repayments.create');;
Route::apiResource('repayments', 'RepaymentController');


