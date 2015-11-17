<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;

Route::get('/', function () {
    return view('welcome');
});

Route::post('oauth/access_token', function() {
    return response()->json(Authorizer::issueAccessToken());
});

//Laravel Socialite Login, Register and Save Routes
Route::get('service/{providerName}/login', 'ServiceAuthController@redirectToProvider');
Route::get('service/{providerName}/data', 'ServiceAuthController@handleProviderData');
Route::post('service/save', 'ServiceAuthController@saveProviderData');