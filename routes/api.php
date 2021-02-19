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

Route::prefix('v1')->group(function () {
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/logout', 'Auth\LoginController@logout');
    Route::get('/login/check', 'Auth\LoginController@check');

    Route::middleware(['auth', 'camelToSnake', 'snakeToCamel'])
        ->prefix('invitations')->group(function () {
            Route::get('/{invitation}', 'InvitationController@show');
            Route::post('/', 'InvitationController@store');
        });
});
