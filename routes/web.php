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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// token page coming this route
Route::get('/token/{id}', 'ApiKeyController@alltoken');
Route::get('token','ApiKeyController@index')->name('tokenwithslug');
//store data using this route
Route::post('webaccess/store/token', 'ApiKeyController@api_store')->name('store.apikeys');
// get token id wise
Route::get('user_vi_token_id_new/{get_token_id}/{user_getting_id}', 'ApiKeyController@getting_token_by_user_new');
Route::get('webaccess/edittoken/{id}','ApiKeyController@edit_token');
// please wait ke bad ka token hai ye
Route::get('webaccess/encrypt_decrypt_new_token/{sentences_amit}/{user_getting_id}','ApiKeyController@getting_token_new_token');
// new route for key file
Route::get('/encrypt','ApiKeyController@page');
Route::post('/encrypt','ApiKeyController@generate')->name('generatetoken');
Route::get('generate_key','TestingController@generate')->name('generatet_token');
Route::get('get_keys','TestingController@get_token')->name('generatetokens');
Route::get('get_ip', 'ApiKeyController@get_ip');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
