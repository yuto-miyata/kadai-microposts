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

Route::get('/', 'MicropostsController@index');

// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');

// ログイン認証
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

//一覧、詳細のログイン認証
Route::group(['middleware' => ['auth']], function() {
    Route::resource('users', 'UserController', ['only' => ['index', 'show']]);
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UserController@followings')->name('users.followings');
        Route::get('followers', 'UserController@followers')->name('users.followers');
        Route::get('favposts', 'UserController@favposts')->name('users.favposts');
    });
    Route::group(['prefix' => 'microposts/{id}'], function () {
        Route::post('fav', 'UserFavController@store')->name('user.fav');
        Route::delete('unfav', 'UserFavController@destroy')->name('user.unfav');
        //Route::get('favposts', 'UserController@favposts')->name('users.favposts');
    });
    Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]);
});
