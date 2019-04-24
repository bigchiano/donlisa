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
Route::post('/check/{param}', 'Check@index');

Route::group(['middleware' => ['auth:api']], function(){
    Route::post('/buy_power', 'PowerController@store');
    Route::post('/buy_airtime', 'AirtimeController@store');
    Route::post('/buy_data', 'DataController@store');
    Route::post('/pay_tv', 'TvController@store');
});

Route::group(['middleware' => ['auth:api']], function(){
    // *** Mark transactions as paid
    Route::post('/buy_airtime/paid/{transaction_id}', 'AirtimeController@paid');
    Route::post('/buy_data/paid/{transaction_id}', 'DataController@paid');
    Route::post('/buy_power/paid/{transaction_id}', 'PowerController@paid');
    Route::post('/buy_tv/paid/{transaction_id}', 'TvController@paid');

    // *** Mark transaction as completed
    Route::post('/buy_airtime/vend/{transaction_id}', 'AirtimeController@vend');


    
    //** * Make calls to irecharge apis

    // *** Airtime
    Route::get('/vend_airtime/{param}', 'AirtimeController@vend_airtime');

    // *** POWER ROUTES
    //get list of power utilities
    Route::get('/list_utilities', 'PowerController@list_utilities');
    //get meter info
    Route::get('/get_meter_info/{param}', 'PowerController@get_meter_info');
    //vend power
    Route::get('/vend_power/{param}', 'PowerController@vend_power');

    // *** DATA ROUTES
    // check data bundle list
    Route::get('/check_data_bundles_list/{param}', 'DataController@check_data_bundles_list');
    // vend data to user
    Route::get('/vend_data/{param}', 'DataController@vend_data');

    // *** TV ROUTES
    // check tv bundles list for either dstv or gotv 
    Route::get('check_tv_bundles_list/{param}', 'TvController@check_tv_bundles_list');
    // chech tv card details to confirm payment
    Route::get('check_tv_card/{param}', 'TvController@check_tv_card');
    // subscribe the tv
    Route::get('vend_tv/{param}', 'TvController@vend_tv');
});