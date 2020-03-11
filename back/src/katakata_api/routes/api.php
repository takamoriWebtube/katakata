<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' =>['api']], function() {
    Route::post('/signup', 'UserController@signup')->name('user/signup');
    Route::post('/signin', 'UserController@signin')->name('user/signin');
    Route::post('/urltoken', 'UserController@checkUrlToken')->name('user/urltoken');
    Route::post('/register', 'UserController@create')->name('user/create');
    Route::get('/oauth/twitter', 'UserController@redirectToTwitter');
    Route::get('/oauth/twitter/callback', 'UserController@handleTwitterCallback');
    Route::post('/credit/webhook', 'CreditController@pay');
});
