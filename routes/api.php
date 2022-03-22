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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::prefix('auth')->group(function () {
    Route::post('login', 'Api\LoginController@login');
    Route::get('token', 'Api\LoginController@checkAuth');
});

Route::prefix('authorized')->group(function () {
    Route::get('/contact/lookup','Api\CX3Controller@contactLookup');
    Route::post('/contact/create','Api\CX3Controller@contactCreation');
    Route::post('/call/journaling','Api\CX3Controller@callJournaling');
    Route::get('/show','Api\CX3Controller@contactIDShow');
});



    

Route::group([
    'prefix' => 'ticket',
],function () {
    Route::post('emails', 'MailgunEmailsController@store');
});

Route::post('testSoap','SurveyController@testSoap');