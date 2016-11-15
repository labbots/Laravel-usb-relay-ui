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

Route::get('/', function () {
    if(Auth::guest()){
        return redirect('login');
    }else{
        return redirect('relays');
    }
});

Route::group(['namespace' => 'Auth','middleware'=>['auth','roles'],'roles'=>['administrator']], function() {
    Route::get('/users', 'UsersController@index');
    Route::get('/users/add', 'UsersController@create');
    Route::post('/users/store', 'UsersController@store');
    Route::post('/users/delete', 'UsersController@delete');
    Route::get('/users/update/{user_id}', 'UsersController@getUpdate');
    Route::post('/users/update/{user_id}', 'UsersController@postUpdate');
});

Route::group(['middleware'=>['auth']], function() {
    Route::get('manage_profile', 'ManageProfileController@getManageProfile');
    Route::post('manage_profile', 'ManageProfileController@postManageProfile');

    Route::get('/home', 'HomeController@index');
    Route::get('/relays', 'RelayController@index');
    Route::post('/set_relay', 'RelayController@postRelay');
    Route::get('/get_relay_status', 'RelayController@getRelayStatus');
    
});

Route::group(['prefix' => 'api/1.0','middleware'=>['apiguard']], function() {
    Route::get('/relay_status', 'RelayController@getRelayStatus');
    Route::post('/relay', 'RelayController@setRelay');
});

   // Authentication Routes...
    //Route::auth();
Route::get('login', 'Auth\AuthController@showLoginForm');
Route::post('login', 'Auth\AuthController@login');

Route::get('logout', 'Auth\AuthController@logout');
Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
Route::post('password/reset', 'Auth\PasswordController@reset');

