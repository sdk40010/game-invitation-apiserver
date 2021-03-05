<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lcobucci\JWT\Signer\Rsa;

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

Route::middleware(['camelToSnake', 'snakeToCamel'])->prefix('v1')->group(function () {
    // 認証が不要なルート
    Route::post('/login', 'Auth\LoginController@login');
    Route::post('/logout', 'Auth\LoginController@logout');
    Route::get('/login/check', 'Auth\LoginController@check');

    Route::prefix('invitations')->group(function () {
        Route::get('/', 'InvitationController@index');
    });

    // 認証が必要なルート
    Route::middleware('auth')->group(function () {
        Route::prefix('invitations')->group(function () {
            // 募集
            Route::get('/{invitation}', 'InvitationController@show');
            Route::post('/', 'InvitationController@store');
            Route::put('/{invitation}', 'InvitationController@update');
            Route::delete('/{invitation}', 'InvitationController@delete');

            // コメント
            Route::prefix('/{invitation}/comments')->group(function () {
                Route::get('/', 'CommentController@index');
                Route::get('/{comment}', 'CommentController@show');
                Route::post('/', 'CommentController@store');
                Route::put('/{comment}', 'CommentController@update');
                Route::delete('/{comment}', 'CommentController@delete');
            });

            // 参加
            Route::prefix('/{invitation}/participations')->group(function () {
                Route::post('/', 'ParticipationController@store');
                Route::delete('/', 'ParticipationController@delete');
            });
        });

        // 返信
        Route::prefix('comments/{comment}')->group(function () {
             Route::get('/replies', 'ReplyController@index');
             Route::post('/replies', 'ReplyController@store');
             Route::put('/replies/{reply}', 'ReplyController@update');
             Route::delete('/replies/{reply}', 'ReplyController@delete');
        });

        // タグ
        Route::prefix('tags')->group(function () {
            Route::get('/', 'TagController@index');
        });
    });
});
