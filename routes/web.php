<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

    //短信测试
    // Route::post('send', 'SmsController@sendCode');
    //短信验证
    Route::any('sendCode', 'SmsController@sendCode');
    Route::any('check', 'SmsController@registerSend');
    Route::any('find', 'SmsController@findPasswordSend');
	Route::any('bind', 'SmsController@bindindPhoneSend');

    //redis 测试
    Route::get('redis', 'RedisController@testredis');





