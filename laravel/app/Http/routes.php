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

Route::get('/test-oauth', function () {
    return view('test.integracaoOAuth');
});


//User Routes
Route::get('user/info', ['middleware' => 'oauth', function(){
    return Authorizer::getResourceOwnerId();
}]);

//Laravel Socialite Login, Data and Login Routes
Route::get('service/{providerName}/login', 'ServiceAuthController@redirectToProvider');
Route::get('service/{providerName}/data', 'ServiceAuthController@handleProviderData');

//Payment Routes
Route::post('payment/{service}/post', 'PaymentController@postPayment');
Route::get('payment/{service}/status', 'PaymentController@getPaymentStatus');
Route::get('payment/{service}/cancel', 'PaymentController@getPaymentCancel');

//Oauth Routes
Route::post('service/login', function() {
    return response()->json(Authorizer::issueAccessToken());
});
Route::post('oauth/access_token', function() {
    return response()->json(Authorizer::issueAccessToken());
});
