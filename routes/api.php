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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// register new user
Route::post('/register', 'UserController@create');
Route::post('/login', 'UserController@login');

// get hash for transactions
Route::post('/getHash/{combined_string}', 'ServiceController@getHash');